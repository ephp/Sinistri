<?php

namespace Ephp\Bundle\StrumentiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/strumenti")
 * @Template()
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="strumenti")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
