<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 21/12/15
 * Time: 23:07
 */

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_homepage")
     */
    public function indexAction()
    {
        return $this->render('backend/index.html.twig');
    }
}