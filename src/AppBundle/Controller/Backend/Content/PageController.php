<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/01/2016
 * Time: 13:28
 */

namespace AppBundle\Controller\Backend\Content;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/content/page")
 */
class PageController extends Controller
{
    /**
     * @Route("/", name="admin_page_index")
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->render('backend/content/page/index.html.twig');
    }
}