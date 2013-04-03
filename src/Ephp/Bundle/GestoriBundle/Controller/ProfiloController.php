<?php

namespace Ephp\Bundle\GestoriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\GestoriBundle\Entity\Gestore;
use Ephp\Bundle\GestoriBundle\Form\GestoreType;
use Ephp\Bundle\GestoriBundle\Form\GestoreNewType;

/**
 * Gestore controller.
 *
 * @Route("/profilo")
 */
class ProfiloController extends Controller {

    /**
     * Lists all Gestore entities.
     *
     * @Route("/", name="profilo")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $gestore = $this->getUser();
        $priorita = array(
            $em->getRepository('EphpSinistriBundle:Priorita')->findOneBy(array('priorita' => 'attenzione'))->getId(),
            $em->getRepository('EphpSinistriBundle:Priorita')->findOneBy(array('priorita' => 'alta'))->getId(),
            $em->getRepository('EphpSinistriBundle:Priorita')->findOneBy(array('priorita' => 'assegnato'))->getId(),
        );
        $priorita_adm = array(
            $em->getRepository('EphpSinistriBundle:Priorita')->findOneBy(array('priorita' => 'nuovo'))->getId(),
            $em->getRepository('EphpSinistriBundle:Priorita')->findOneBy(array('priorita' => 'riattivato'))->getId(),
        );
        $prima_pagina = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('gestore' => $gestore->getId(), 'prima_pagina' => true), array('claimant' => 'ASC'));
        $attenzione = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('gestore' => $gestore->getId(), 'priorita' => $priorita), array('claimant' => 'ASC'));
        $nuove = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('priorita' => $priorita_adm), array('claimant' => 'ASC'));

        return array(
            'gestore' => $gestore,
            'oggi' => new \DateTime(),
            'eventi_oggi' => $this->eventi($gestore),
            'eventi_ieri' => $this->eventi($gestore, -1),
            'eventi_domani' => $this->eventi($gestore, 1),
            'prima_pagina' => $prima_pagina,
            'attenzione' => $attenzione,
            'nuove' => $nuove,
        );
    }

    private function eventi(\Ephp\Bundle\GestoriBundle\Entity\Gestore $gestore, $day = 0) {
        $em = $this->getEm();
        $calendario = $this->getCalendar();
        $eventi = $em->getRepository('EphpSinistriBundle:Evento')->prossimiEventi($calendario, $gestore);
        $oggi = new \DateTime();
        $out = array();
        foreach ($eventi as $evento) {
            /* @var $evento \Ephp\Bundle\SinistriBundle\Entity\Evento */
            $t = $evento->getDataOra();
            if (date('d-m-Y', $t->getTimestamp()) == date('d-m-Y', $oggi->getTimestamp() + $day * 86400)) {
                $out[] = $evento;
            }
        }
        return $out;
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
