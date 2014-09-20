<?php

namespace JDJ\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JDJSearchBundle:Default:index.html.twig', array('name' => $name));
    }
}
