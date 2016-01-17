<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 14/01/2016
 * Time: 13:03
 */

namespace AppBundle\Controller\Backend\Content;

use AppBundle\Form\Type\PageType;
use AppBundle\Form\Type\SimpleBlockType;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/content/blocks/simple")
 */
class SimpleBlockController extends Controller
{
    /**
     * @Route("/new", requirements={"id" = ".+"}, name="admin_content_block_simple_new")
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $block = new SimpleBlock();
        $block
            ->setParentDocument($this->getParent());

        $form = $this->createForm(new SimpleBlockType(), $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getManager();
            $em->persist($block);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'page_show',
                array('id' => $block->getName())
            ));
        }

        return $this->render("backend/content/block/simple/new.html.twig", array(
            'block' => $block,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", requirements={"id" = ".+"}, name="admin_content_block_simple_edit")
     *
     * @param Request $request
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $page = $this->findOr404($id);

        $form = $this->createForm(new PageType(), $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'admin_homepage',
                array('id' => $page->getName())
            ));
        }

        return $this->render("backend/content/block/simple/edit.html.twig", array(
            'block' => $page,
            'form' => $form->createView(),
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