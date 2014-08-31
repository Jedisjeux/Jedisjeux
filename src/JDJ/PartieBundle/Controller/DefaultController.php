<?php

namespace JDJ\PartieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JDJPartieBundle:Default:index.html.twig', array('name' => $name));
    }
}
