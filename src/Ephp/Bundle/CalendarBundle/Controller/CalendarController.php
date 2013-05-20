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
class CalendarController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Lists all Scheda entities.
     *
     * @Route("-completo/{calendario}/{gestore}", name="calendario_completo", defaults={"gestore"=""})
     * @Template()
     */
    public function indexAction($calendario, $gestore) {
        $mode = 0;
        $_calendario = $this->getRepository('EphpCalendarBundle:Calendario');
        $calendario = $_calendario->findOneBy(array('sigla' => $calendario));
        if ($calendario && $gestore) {
            $gestore = $this->findOneBy('EphpGestoriBundle:Gestore', array('sigla' => $gestore));
            $entities = $this->getRepository('EphpCalendarBundle:Evento')->prossimiEventi($calendario, $gestore, 100);
        } else {
            $entities = $this->getRepository('EphpCalendarBundle:Evento')->prossimiEventi($calendario, null, 100);
        }
        $gestori = $this->findBy('EphpGestoriBundle:Gestore', array(), array('sigla' => 'ASC'));
        return array(
            'entities' => $entities,
            'mode' => $mode,
            'ospedale' => $calendario,
            'gestori' => $gestori,
            'anni' => range(7, date('y'))
        );
    }

}
