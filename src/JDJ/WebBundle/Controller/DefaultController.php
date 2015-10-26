<?php

namespace JDJ\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //return $this->render('default/index.html.twig', array());
        return $this->redirect($this->generateUrl('compta_home'));
    }
}
