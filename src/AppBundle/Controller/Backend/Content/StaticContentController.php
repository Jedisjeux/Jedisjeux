<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 01/02/2016
 * Time: 13:54
 */

namespace AppBundle\Controller\Backend\Content;

use AppBundle\Form\Type\PageType;
use Doctrine\ODM\PHPCR\DocumentManager;
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
 * @Route("/content/page/static")
 */
class StaticContentController extends Controller
{
    /**
     * @Route("/", name="admin_static_content_index")
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

        return $this->render('backend/content/page/static_content/index.html.twig', array(
            'pages' => $pages,
        ));
    }

    /**
     * @Route("/new", name="admin_static_content_new")
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
                array('name' => $page->getName())
            ));
        }

        return $this->render("backend/content/page/static_content/new.html.twig", array(
            'page' => $page,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{name}/edit", requirements={"name" = ".+"}, name="admin_static_content_edit")
     *
     * @param Request $request
     * @param string $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $name)
    {
        $page = $this->findOr404($name);

        $form = $this->createForm(new PageType(), $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'page_show',
                array('name' => $page->getName())
            ));
        }

        return $this->render("backend/content/page/static_content/edit.html.twig", array(
            'page' => $page,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{name}/delete", name="admin_static_content_delete")
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
            'admin_static_content_index'
        ));
    }

    /**
     * @param string $name
     * @return StaticContent
     */
    protected function findOr404($name)
    {
        $page = $this
            ->getRepository()
            ->findStaticContent($name);

        if (null === $page) {
            throw new NotFoundHttpException(sprintf("Page %s not found", $name));
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