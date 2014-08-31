<?php

namespace JDJ\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JDJCommentBundle:Default:index.html.twig', array('name' => $name));
    }
}
