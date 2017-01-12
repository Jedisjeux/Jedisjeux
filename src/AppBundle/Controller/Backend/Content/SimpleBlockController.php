<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 14/01/2016
 * Time: 13:03
 */

namespace AppBundle\Controller\Backend\Content;

use AppBundle\Form\Type\SimpleBlockType;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\NodeHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/", name="admin_block_simple_index")
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

        return $this->render('backend/content/block/simple/index.html.twig', array(
            'blocks' => $blocks,
        ));
    }

    /**
     * @Route("/{name}/delete", name="admin_block_simple_delete")
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
            'admin_block_simple_index'
        ));
    }

    /**
     * @Route("/new", name="admin_block_simple_new")
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

            return $this->redirect($this->generateUrl('admin_block_simple_index'));
        }

        return $this->render("backend/content/block/simple/new.html.twig", array(
            'block' => $block,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{name}/edit", requirements={"name" = ".+"}, name="admin_block_simple_edit")
     *
     * @param Request $request
     * @param string $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $name)
    {
        $page = $this->findOr404($name);

        $form = $this->createForm(new SimpleBlockType(), $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_block_simple_index'));
        }

        return $this->render("backend/content/block/simple/edit.html.twig", array(
            'block' => $page,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param string $name
     * @return SimpleBlock
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
        return $this->get('sylius.manager.simple_block');
    }

}