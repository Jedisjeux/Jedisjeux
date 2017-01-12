<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 17/01/16
 * Time: 18:22
 */

namespace AppBundle\Controller\Frontend\Content;

use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\Repository\RepositoryFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/blocks")
 */
class SimpleBlockController extends Controller
{
    /**
     * @Route("/{id}", requirements={"id" = ".+"}, name="block_simple_show")
     *
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $page = $this->findOr404($id);

        return $this->render("frontend/page/show.html.twig", array(
            'page' => $page,
        ));
    }

    /**
     * @param string $id
     * @return SimpleBlock
     */
    protected function findOr404($id)
    {
        $page = $this
            ->getRepository()
            ->find($id);

        if (null === $page) {
            throw new NotFoundHttpException(sprintf("Block %s not found", $id));
        }

        return $page;
    }

    /**
     * @return Generic
     */
    protected function getParent()
    {
        return $this->getManager()->find(null, '/cms/blocks/simple');
    }

    /**
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->get('sylius.repository.simple_block');
    }

    /**
     * @return DocumentManager
     */
    public function getManager()
    {
        return $this->get('sylius.manager.static_content');
    }
}