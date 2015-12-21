<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 21/12/2015
 * Time: 16:56
 */

namespace AppBundle\Controller\Backend;

use JDJ\JeuBundle\Entity\Theme;
use JDJ\JeuBundle\Form\ThemeType;
use JDJ\JeuBundle\Repository\ThemeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/theme")
 */
class ThemeController extends Controller
{
    /**
     * @Route("/", name="admin_theme_index")
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array('name' => 'asc'));

        $themes = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setCurrentPage($request->get('page', 1));

        return $this->render('backend/theme/index.html.twig', array(
            'themes' => $themes,
        ));
    }

    /**
     * @Route("/new", name="admin_theme_new")
     *
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $theme = new Theme();
        $form = $this->createForm(new ThemeType(), $theme);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'admin_theme_index',
                array('id' => $theme->getId())
            ));
        }

        return $this->render('backend/theme/new.html.twig', array(
            'theme' => $theme,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="admin_theme_edit")
     *
     * @ParamConverter("theme", class="JDJJeuBundle:Theme")
     *
     * @param Request $request
     * @param Theme $theme
     *
     * @return array
     */
    public function updateAction(Request $request, Theme $theme)
    {
        $form = $this->createForm(new ThemeType(), $theme);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'admin_theme_index',
                array('id' => $theme->getId())
            ));
        }

        return $this->render('backend/theme/edit.html.twig', array(
            'theme' => $theme,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="admin_theme_delete")
     *
     * @ParamConverter("theme", class="JDJJeuBundle:Theme")
     *
     * @param Theme $theme
     *
     * @return RedirectResponse
     */
    public function deleteAction(Theme $theme)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($theme);
        $em->flush();

        return $this->redirect($this->generateUrl(
            'admin_theme_index'
        ));
    }

    /**
     * @return ThemeRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('JDJJeuBundle:Theme');
    }
}