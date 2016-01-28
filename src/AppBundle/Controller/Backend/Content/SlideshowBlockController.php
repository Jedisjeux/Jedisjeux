<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 26/01/2016
 * Time: 16:52
 */

namespace AppBundle\Controller\Backend\Content;

use AppBundle\Form\Type\SlideshowBlockType;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\NodeHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/content/blocks/slideshow")
 */
class SlideshowBlockController extends Controller
{
    /**
     * @Route("/", name="admin_slideshow_block_index")
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array('title' => 'asc'));

        $blocks = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setCurrentPage($request->get('page', 1));

        return $this->render('backend/content/block/slideshow/index.html.twig', array(
            'blocks' => $blocks,
        ));
    }

    /**
     * @Route("/new", name="admin_slideshow_block_new")
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $block = new SlideshowBlock();
        $block
            ->setParentDocument($this->getParent());

        $block
            ->addChildren(new ImagineBlock());

        $form = $this->createForm(new SlideshowBlockType(), $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getManager();
            $em->persist($block);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_slideshow_block_index'));
        }

        return $this->render("backend/content/block/slideshow/new.html.twig", array(
            'block' => $block,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{name}/edit", requirements={"name" = ".+"}, name="admin_slideshow_block_edit")
     *
     * @param Request $request
     * @param string $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $name)
    {
        $page = $this->findOr404($name);

        $form = $this->createForm(new SlideshowBlockType(), $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_slideshow_block_index'));
        }

        return $this->render("backend/content/block/slideshow/edit.html.twig", array(
            'block' => $page,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{name}/delete", name="admin_slideshow_block_delete")
     *
     * @param string $name
     *
     * @return RedirectResponse
     */
    public function deleteAction($name)
    {
        $page = $this->findOr404($name);
        $this->getManager()->remove($page);
        $this->getManager()->flush();

        return $this->redirect($this->generateUrl(
            'admin_slideshow_block_index'
        ));
    }

    /**
     * @param string $name
     * @return SlideshowBlock
     */
    protected function findOr404($name)
    {
        $contentBasepath = '/cms/blocks';

        $page = $this
            ->getRepository()
            ->find($contentBasepath . '/' . $name);

        if (null === $page) {
            throw new NotFoundHttpException(sprintf("Block %s not found", $name));
        }

        return $page;
    }

    /**
     * @return Generic
     */
    protected function getParent()
    {
        $contentBasepath = '/cms/blocks';
        $parent = $this->getManager()->find(null, $contentBasepath);

        if (null === $parent) {
            $session = $this->getManager()->getPhpcrSession();
            NodeHelper::createPath($session, $contentBasepath);
            $parent = $this->getManager()->find(null, $contentBasepath);
        }

        return $parent;
    }

    /**
     * @return DocumentManager
     */
    public function getManager()
    {
        return $this->get('sylius.manager.slideshow_block');
    }

    /**
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->get('sylius.repository.slideshow_block');
    }
}