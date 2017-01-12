<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 13/01/2016
 * Time: 12:11
 */

namespace AppBundle\Controller\Frontend;

use Doctrine\ODM\PHPCR\DocumentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sylius\Bundle\ContentBundle\Doctrine\ODM\PHPCR\StaticContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/page")
 */
class PageController extends Controller
{
    /**
     * @Route("/{name}", requirements={"name" = ".+"}, name="page_show")
     *
     * @param string $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($name)
    {
        $page = $this->findOr404($name);

        return $this->render("frontend/content/page/show.html.twig", array(
            'page' => $page,
        ));
    }

    /**
     * @param string $name
     * @return StaticContent
     */
    protected function findOr404($name) {
        $page = $this
            ->getRepository()
            ->findStaticContent($name);

        if (null === $page) {
            throw new NotFoundHttpException(sprintf("Page %s not found", $name));
        }

        return $page;
    }

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