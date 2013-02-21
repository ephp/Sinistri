<?php

namespace Ephp\Bundle\SinistriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\CalendarBundle\Vendor\ICalCreator\VCalendar;
use Ephp\Bundle\CalendarBundle\Vendor\ICalCreator\ICalUtilityFunctions;

/**
 * Scheda controller.
 *
 * @Route("/calendario-sinistri")
 */
class CalendarController extends Controller {

    /**
     * Lists all Scheda entities.
     *
     * @Route("/{gestore}", name="calendario_sinistri", defaults={"gestore"=""})
     * @Template()
     */
    public function indexAction($gestore) {
        $em = $this->getEm();
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $calendario = $this->getCalendar();
        if ($gestore) {
            $gestore = $_gestore->findOneBy(array('sigla' => $gestore));
            $entities = $em->getRepository('EphpSinistriBundle:Evento')->prossimiEventi($calendario, $gestore);
        } else {
            $entities = $em->getRepository('EphpSinistriBundle:Evento')->prossimiEventi($calendario, null);
        }
        $gestori = $_gestore->findBy(array(), array('sigla' => 'ASC'));
        return array(
            'oggi' => new \DateTime(),
            'entities' => $entities,
            'gestore' => $gestore,
            'gestori' => $gestori,
            'anni' => range(7, date('y'))
        );
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-ical/{gestore}.ics", name="calendario_sinistri_ical", defaults={"gestore"="completo","_format"="text/calendar"})
     * @Template()
     */
    public function iCalAction($gestore) {
        $em = $this->getEm();
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $calendario = $this->getCalendar();
        if ($gestore != 'completo') {
            $gestore = $_gestore->findOneBy(array('sigla' => $gestore));
            $entities = $em->getRepository('EphpSinistriBundle:Evento')->prossimiEventi($calendario, $gestore);
        } else {
            $entities = $em->getRepository('EphpSinistriBundle:Evento')->prossimiEventi($calendario, null);
        }

        set_time_limit(3600);
        $tz = "Europe/Rome";                   // define time zone
        $config = array("unique_id" => "jfclaims" // set a (site) unique id
            , "TZID" => $tz, "NL" => "\n");          // opt. "calendar" timezone
        $v = new VCalendar($config);
        $v->setProperty("method", "PUBLISH");
        $v->setProperty("x-wr-calname", "Calendario sinistri " . ($gestore != 'completo' ? $gestore->getNome() : 'completo'));
        $v->setProperty("X-WR-CALDESC", "Calendario JFClaims dei sinistri");
        $v->setProperty("X-WR-TIMEZONE", $tz);
        $xprops = array("X-LIC-LOCATION" => $tz);
        ICalUtilityFunctions::createTimezone($v, $tz, $xprops);

        foreach ($entities as $evento) {
            /* @var $evento \Ephp\Bundle\SinistriBundle\Entity\Evento */
            $vevent = & $v->newComponent("vevent");
            /* @var $vevent \Ephp\Bundle\CalendarBundle\Vendor\ICalCreator\VEvent */
            $vevent->setProperty("dtstart", date('Ymd', $evento->getDataOra()->getTimestamp()), array("VALUE" => "DATE"));
            $vevent->setProperty("summary", $evento->getScheda()->getClaimant() . ': ' . $evento->getTitolo());
            $vevent->setProperty("description", $evento->getNote());
            $vevent->setProperty("comment", "In carico a {$evento->getScheda()->getGestore()->getNome()}");
            $vevent->setProperty("attendee", $evento->getScheda()->getGestore()->getEmail());
            $valarm = & $vevent->newComponent("valarm");
            /* @var $valarm \Ephp\Bundle\CalendarBundle\Vendor\ICalCreator\VAlarm */
            $valarm->setProperty("action", "DISPLAY");
            $valarm->setProperty("description", $evento->getScheda()->getClaimant() . ': ' . $evento->getTitolo() . ' - ' . $evento->getNote());
            $d = sprintf("%04d%02d%02d %02d%02d%02d", date('Y', $evento->getDataOra()->getTimestamp()), date('m', $evento->getDataOra()->getTimestamp()), date('d', $evento->getDataOra()->getTimestamp()), 9, 30, 0);
            ICalUtilityFunctions::transformDateTime($d, $tz, "UTC", "Ymd\THis\Z");
            $valarm->setProperty("trigger", $d);
        }

        return new \Symfony\Component\HttpFoundation\Response($v->createCalendar(), 200, array('Content-Type: text/calendar; charset=utf-8', 'Cache-Control: max-age=10'));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-csv/{gestore}.csv", name="calendario_sinistri_csv", defaults={"gestore"="completo","_format"="csv"})
     * @Template()
     */
    public function csvAction($gestore) {
        $em = $this->getEm();
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $calendario = $this->getCalendar();
        if ($gestore != 'completo') {
            $gestore = $_gestore->findOneBy(array('sigla' => $gestore));
            $entities = $em->getRepository('EphpSinistriBundle:Evento')->prossimiEventi($calendario, $gestore);
        } else {
            $entities = $em->getRepository('EphpSinistriBundle:Evento')->prossimiEventi($calendario, null);
        }

        set_time_limit(3600);

        $colonne = array('Oggetto', 'Data di inizio', 'Ora di inizio', 'Data di fine', 'Ora di fine', 'evento Tutto il giorno', 'Promemoria On/Off', 'Data promemoria', 'Ora promemoria', 'Organizzatore riunione', 'Descrizione', 'Ubicazione', 'Privato');
        $righe = array();
        $righe[] = implode(',', $colonne);
        foreach ($entities as $evento) {

            $riga = array(
                trim($evento->getScheda()->getClaimant() . ': ' . str_replace(array(',', "\n", "\r"), array(' - ', ' ', ' '), $evento->getTitolo())),
                date('Y/m/d', $evento->getDataOra()->getTimestamp()),
                '09:00 AM',
                '',
                '',
                'TRUE',
                'TRUE',
                date('Y/m/d', $evento->getDataOra()->getTimestamp()),
                '09:00 AM',
                '',
                trim(str_replace(array(',', "\n", "\r"), array(' - ', ' ', ' '), $evento->getNote())),
                trim(str_replace(array(',', "\n", "\r"), array(' - ', ' ', ' '), $evento->getScheda()->getOspedale()->getNome())),
                'TRUE',
            );
            $righe[] = implode(',', $riga);
        }

        return new \Symfony\Component\HttpFoundation\Response(implode("\n", $righe));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("/{gestore}/{pagina}", name="calendario_sinistri_scroll", defaults={"gestore"=""})
     * @Template("EphpSinistriBundle:Calendar:index/tbody.html.twig")
     */
    public function scrollAction($gestore, $pagina) {
        $em = $this->getEm();
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $calendario = $this->getCalendar();
        if ($gestore) {
            $gestore = $_gestore->findOneBy(array('sigla' => $gestore));
            $entities = $em->getRepository('EphpSinistriBundle:Evento')->prossimiEventi($calendario, $gestore, 100, ($pagina - 1) * 100);
        } else {
            $entities = $em->getRepository('EphpSinistriBundle:Evento')->prossimiEventi($calendario, null, 100, ($pagina - 1) * 100);
        }
        return array(
            'oggi' => new \DateTime(),
            'entities' => $entities,
            'gestore' => $gestore,
            'index' => 100 * $pagina + 1,
        );
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-cron", name="calendario_sinistri_cron", defaults={"_format"="json"})
     */
    public function cronAction() {
        $em = $this->getEm();
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $gestori = $_gestore->findAll();
        $out = array('gestori' => count($gestori), 'email' => array());
        foreach ($gestori as $gestore) {
            $out['email'][] = $this->sendEmailAction($gestore);
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }

    private function sendEmailAction(\Ephp\Bundle\ACLBundle\Entity\Gestore $gestore) {
        $em = $this->getEm();
        $calendario = $this->getCalendar();
        $eventi = $em->getRepository('EphpSinistriBundle:Evento')->prossimiEventi($calendario, $gestore);
        $oggi = new \DateTime();
        $send = array();
        foreach ($eventi as $evento) {
            /* @var $evento \Ephp\Bundle\SinistriBundle\Entity\Evento */
            $t = $evento->getDataOra();
            if (date('d-m-Y', $t->getTimestamp()) == date('d-m-Y', $oggi->getTimestamp())) {
                $send[] = $evento;
            }
        }
        if (count($send) > 0) {
            $message = \Swift_Message::newInstance()
                    ->setSubject("[JFCLAIMS] agenda {$gestore->getNome()} " . date('d-m-Y', $oggi->getTimestamp()))
                    ->setFrom($this->container->getParameter('email_robot'))
                    ->setTo(trim($gestore->getEmail()))
                    ->setReplyTo($this->container->getParameter('email_robot'), "No-Reply")
                    ->setBody($this->renderView("EphpSinistriBundle:Calendar:email/agenda_giornaliera.txt.twig", array('gestore' => $gestore, 'entities' => $send, 'oggi' => $oggi)))
                    ->addPart($this->renderView("EphpSinistriBundle:Calendar:email/agenda_giornaliera.html.twig", array('gestore' => $gestore, 'entities' => $send, 'oggi' => $oggi)), 'text/html');
            ;
            $message->getHeaders()->addTextHeader('X-Mailer', 'PHP v' . phpversion());
            $this->get('mailer')->send($message);
        }
        return array($gestore->getSigla() => count($send));
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEm() {
        return $this->getDoctrine()->getEntityManager();
    }

    private function getCalendar() {
        $_cal = $this->getEm()->getRepository('EphpCalendarBundle:Calendario');
        /* @var $_cal \Ephp\Bundle\CalendarBundle\Entity\CalendarioRepository */
        $cal = $_cal->findOneBy(array('sigla' => 'JFC-SX'));
        if (!$cal) {
            $cal = $_cal->createCalendario('JFC-SX', 'JF-Claims Sinistri');
            $_tipo = $this->getEm()->getRepository('EphpCalendarBundle:Tipo');
            /* @var $_tipo \Ephp\Bundle\CalendarBundle\Entity\TipoRepository */
            $_tipo->createTipo('ASC', 'Analisi Sinistri e Copertura', 'aaffaa', $cal);
            $_tipo->createTipo('VIM', 'Verifica Incarichi e Medici', 'aaffaa', $cal);
            $_tipo->createTipo('RPM', 'Ricerca Polizze e Medici', 'aaffaa', $cal);
            $_tipo->createTipo('RER', 'Relazione e Riserva', 'aaffaa', $cal);
            $_tipo->createTipo('RSA', 'Richiesta di SA', 'aaffaa', $cal);
            $_tipo->createTipo('TAX', 'Trattative e Aggiornamenti', 'aaffaa', $cal);
            $_tipo->createTipo('JWB', 'J-Web Claims', 'ffaaaa', $cal);
            $_tipo->createTipo('CNT', 'Cancelleria Telematiche', 'aaffff', $cal);
            $_tipo->createTipo('RVP', 'Ravinale Piemonte', 'ffaaff', $cal);
            $_tipo->createTipo('OTH', 'Attività manuali', 'ffffaa', $cal);
            $_tipo->createTipo('RIS', 'Rischedulazione', 'aaaaaa', $cal);
        }
        return $cal;
    }

}
