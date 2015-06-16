<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 16/06/2015
 * Time: 01:52
 */

namespace JDJ\ComptaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @Route("/")
 */
class IndexController extends Controller
{
    /**
     * Compta Home
     *
     * @Route("/", name="compta_home")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('compta/index.html.twig');
    }
}