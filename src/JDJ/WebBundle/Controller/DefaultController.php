<?php

namespace JDJ\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JDJWebBundle:Default:index.html.twig', array());
    }
}
