<?php

namespace Ephp\Bundle\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Scheda controller.
 *
 * @Route("/calendario")
 */
class CalendarController extends Controller
{
    /**
     * Lists all Scheda entities.
     *
     * @Route("/{calendario}/{gestore}", name="calendario", defaults={"gestore"=""})
     * @Template()
     */
    public function indexAction($calendario, $gestore) {
        $em = $this->getEm();
        $mode = 0;
        $_calendario = $em->getRepository('EphpCalendarBundle:Calendario');
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $calendario = $_calendario->findOneBy(array('sigla' => $calendario));
        if ($calendario && $gestore) {
            $gestore = $_gestore->findOneBy(array('sigla' => $gestore));
            $entities = $em->getRepository('EphpCalendarBundle:Evento')->prossimiEventi($calendario, $gestore, 100);
        } else {
            $entities = $em->getRepository('EphpCalendarBundle:Evento')->prossimiEventi($calendario, null, 100);
        }
        $gestori = $_gestore->findBy(array(), array('sigla' => 'ASC'));
        return array(
            'entities' => $entities,
            'mode' => $mode,
            'ospedale' => $calendario,
            'anno' => $anno < 10 ? '0' . $anno : $anno,
            'gestori' => $gestori,
            'anni' => range(7, date('y'))
        );
    }

}
