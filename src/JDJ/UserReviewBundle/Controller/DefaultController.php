<?php

namespace JDJ\UserReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JDJUserReviewBundle:Default:index.html.twig', array('name' => $name));
    }
}
