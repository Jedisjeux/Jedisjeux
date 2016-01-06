<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/01/2016
 * Time: 14:06
 */

namespace AppBundle\Controller\Frontend;

use JDJ\JeuBundle\Entity\Theme;
use JDJ\JeuBundle\Repository\JeuRepository;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/jeu/theme")
 */
class ThemeController extends Controller
{
    /**
     * Finds and displays a Theme entity.
     *
     * @Route("/{id}/{slug}", name="theme_show")
     *
     * @ParamConverter("theme", class="JDJJeuBundle:Theme")
     *
     * @param Request $request
     * @param Theme $theme
     * @param string $slug
     * @return RedirectResponse|Response
     */
    public function showAction(Request $request, Theme $theme, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * Redirect the slug is incorrect
         */
        if ($slug !== $theme->getSlug()) {
            return $this->redirect($this->generateUrl('theme_show', array(
                    'id' => $theme->getId(),
                    'slug' => $theme->getSlug(),
                )
            ));
        }

        /** @var JeuRepository $jeuReposititory */
        $jeuReposititory = $em->getRepository('JDJJeuBundle:Jeu');
        /** @var Pagerfanta $jeux */
        $jeux = $jeuReposititory->createPaginator(array("theme" => $theme));
        $jeux->setMaxPerPage(16);
        $jeux->setCurrentPage($request->get('page', 1));

        return $this->render('frontend/theme/show.html.twig', array(
            'entity'      => $theme,
            'jeux'        => $jeux,
        ));
    }
}