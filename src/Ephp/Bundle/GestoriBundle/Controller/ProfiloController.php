<?php

namespace Ephp\Bundle\GestoriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Gestore controller.
 *
 * @Route("/profilo")
 */
class ProfiloController extends Controller {
    
    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Lists all Gestore entities.
     *
     * @Route("/", name="profilo")
     * @Template()
     */
    public function indexAction() {

        $gestore = $this->getUser();
        $priorita = array(
            $this->findOneBy('EphpSinistriBundle:Priorita', array('priorita' => 'attenzione'))->getId(),
            $this->findOneBy('EphpSinistriBundle:Priorita', array('priorita' => 'alta'))->getId(),
            $this->findOneBy('EphpSinistriBundle:Priorita', array('priorita' => 'assegnato'))->getId(),
        );
        $priorita_adm = array(
            $this->findOneBy('EphpSinistriBundle:Priorita', array('priorita' => 'nuovo'))->getId(),
            $this->findOneBy('EphpSinistriBundle:Priorita', array('priorita' => 'riattivato'))->getId(),
        );
        $prima_pagina = $this->findBy('EphpSinistriBundle:Scheda', array('gestore' => $gestore->getId(), 'prima_pagina' => true), array('claimant' => 'ASC'));
        $attenzione = $this->findBy('EphpSinistriBundle:Scheda', array('gestore' => $gestore->getId(), 'priorita' => $priorita), array('claimant' => 'ASC'));
        $nuove = $this->findBy('EphpSinistriBundle:Scheda', array('priorita' => $priorita_adm), array('claimant' => 'ASC'));
        
        $tabs = $this->findBy('EphpSinistriBundle:StatoOperativo', array('tab' => true));
        $private = $pubbliche = array();
        foreach($tabs as $tab) {
            $pu = $pr = array();
            $all = $this->findBy('EphpSinistriBundle:Scheda', array('stato_operativo' => $tab->getId()), array('claimant' => 'ASC'));
            foreach($all as $one) {
                /* @var $one \Ephp\Bundle\SinistriBundle\Entity\Scheda */
                if($one->getGestore()->getId() == $gestore->getId()) {
                    $pr[] = $one;
                } else {
                    $pu[] = $one;
                }
            }
            
            $private[$tab->getId()] = $pr;
            $pubbliche[$tab->getId()] = $pu;
        }
        
        return array(
            'tabs' => $tabs,
            'private' => $private,
            'pubbliche' => $pubbliche,
            'gestore' => $gestore,
            'oggi' => new \DateTime(),
            'prima_pagina' => $prima_pagina,
            'attenzione' => $attenzione,
            'nuove' => $nuove,
            'gestori' => $this->findBy('EphpGestoriBundle:Gestore', array(), array('sigla' => 'ASC')),
        );
    }
    
}
