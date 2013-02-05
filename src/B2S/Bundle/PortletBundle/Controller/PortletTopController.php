<?php

namespace B2S\Bundle\PortletBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/portlets/portlet/nomodifica")
 */
class PortletTopController extends Controller
{
    /**
     * @Route("/")
     * @Template("B2SPortletBundle:Portlets:Top/index.html.twig")
     */
    public function indexAction()
    {
        return array(
            'layout' => $this->getRequest()->get('layout', 'default'), 
            'titolo' => $this->getRequest()->get('titolo', false),
            );
    }
}
