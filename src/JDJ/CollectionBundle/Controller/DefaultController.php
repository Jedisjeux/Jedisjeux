<?php

namespace JDJ\CollectionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JDJCollectionBundle:Default:index.html.twig', array('name' => $name));
    }
}
