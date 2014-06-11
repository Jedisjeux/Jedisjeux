<?php

namespace JDJ\JeuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JDJJeuBundle:Default:index.html.twig');
    }
}
