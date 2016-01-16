<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/01/2016
 * Time: 13:28
 */

namespace AppBundle\Controller\Backend\Content;

use AppBundle\Form\Type\PageType;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\NodeHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sylius\Bundle\ContentBundle\Doctrine\ODM\PHPCR\StaticContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/page")
 */
class PageController extends Controller
{
    /**
     * @Route("/", name="admin_page_index")
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array('title' => 'asc'));

        $pages = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setCurrentPage($request->get('page', 1));

        return $this->render('backend/page/index.html.twig', array(
            'pages' => $pages,
        ));
    }

    /**
     * @Route("/new", requirements={"id" = ".+"}, name="admin_page_new")
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $page = new StaticContent();
        $page
            ->setParentDocument($this->getParent());

        $form = $this->createForm(new PageType(), $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'page_show',
                array('id' => $page->getName())
            ));
        }

        return $this->render("backend/page/new.html.twig", array(
            'page' => $page,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", requirements={"id" = ".+"}, name="admin_page_edit")
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
                'page_show',
                array('id' => $page->getName())
            ));
        }

        return $this->render("backend/page/edit.html.twig", array(
            'page' => $page,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="admin_page_delete")
     *
     * @param string $id
     *
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $page = $this->findOr404($id);
        $this->getManager()->remove($page);
        $this->getManager()->flush();

        return $this->redirect($this->generateUrl(
            'admin_page_index'
        ));
    }

    /**
     * @param string $id
     * @return StaticContent
     */
    protected function findOr404($id)
    {
        $page = $this
            ->getRepository()
            ->findStaticContent($id);

        if (null === $page) {
            throw new NotFoundHttpException(sprintf("Page %s not found", $id));
        }

        return $page;
    }

    /**
     * @return Generic
     */
    protected function getParent()
    {
        return $this->getManager()->find(null, '/cms/pages');
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