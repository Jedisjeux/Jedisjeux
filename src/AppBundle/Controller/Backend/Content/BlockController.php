<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 26/01/2016
 * Time: 12:58
 */

namespace AppBundle\Controller\Backend\Content;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/content/blocks")
 */
class BlockController extends Controller
{
    /**
     * @Route("/", name="admin_block_index")
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->render('backend/content/block/index.html.twig');
    }
}