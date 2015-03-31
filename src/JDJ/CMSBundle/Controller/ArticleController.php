<?php

namespace JDJ\CMSBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ArticleController
 * @package JDJ\CMSBundle\Controller
 */
class ArticleController extends Controller
{
    /**
     * @Route("/article/new", name="article_new")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        return $this->render('cms/article/new.html.twig');
    }

    /**
     * @Route("/article/{article}/{slug}", name="article_show")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($article, $slug)
    {
        return $this->render('cms/article/show.html.twig');
    }
}
