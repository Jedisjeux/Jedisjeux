<?php

namespace JDJ\LudographieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JDJLudographieBundle:Default:index.html.twig', array('name' => $name));
    }
}
