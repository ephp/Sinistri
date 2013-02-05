<?php

namespace Ephp\Bundle\SinistriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\SinistriBundle\Entity\Scheda;

/**
 * Scheda controller.
 *
 * @Route("/tabellone")
 */
class SchedaController extends Controller {

    /**
     * Lists all Scheda entities.
     *
     * @Route("/", name="tabellone")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Scheda entity.
     *
     * @Route("/{id}/show", name="tabellone_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }

        return array(
            'entity' => $entity,
        );
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("/import", name="tabelone_import")
     * @Template()
     */
    public function importAction() {
        
    }

}

