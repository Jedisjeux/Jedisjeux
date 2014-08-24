<?php

namespace JDJ\ComptaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JDJComptaBundle:Default:index.html.twig', array('name' => $name));
    }
}
