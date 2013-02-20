<?php

namespace Ephp\Bundle\SinistriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
