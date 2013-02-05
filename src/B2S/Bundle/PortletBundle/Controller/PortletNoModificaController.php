<?php

namespace B2S\Bundle\PortletBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/portlets/portlet/nomodifica")
 */
class PortletNoModificaController extends Controller
{
    /**
     * @Route("/")
     * @Template("B2SPortletBundle:Portlets:NoModifica/index.html.twig")
     */
    public function indexAction()
    {
        return array('layout' => 'default');
    }
}
