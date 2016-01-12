<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/01/2016
 * Time: 13:28
 */

namespace AppBundle\Controller\Backend;

use Doctrine\ODM\PHPCR\DocumentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sylius\Bundle\ContentBundle\Doctrine\ODM\PHPCR\StaticContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/page")
 */
class PageController extends Controller
{
    /**
     * @return StaticContentRepository
     */
    public function getRepository()
    {
        return $this->get('sylius.repository.static_content');
    }

    /**
     * @return DocumentManager
     */
    public function getManager()
    {
        return $this->get('sylius.manager.static_content');
    }
}