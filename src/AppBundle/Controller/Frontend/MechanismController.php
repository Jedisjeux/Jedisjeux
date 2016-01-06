<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/01/2016
 * Time: 13:39
 */

namespace AppBundle\Controller\Frontend;

use JDJ\JeuBundle\Entity\Mechanism;
use JDJ\JeuBundle\Repository\JeuRepository;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/jeu/mecanisme")
 */
class MechanismController extends Controller
{
    /**
     * Finds and displays a Mechanism entity.
     *
     * @Route("/{id}/{slug}", name="mechanism_show")
     *
     * @ParamConverter("mechanism", class="JDJJeuBundle:Mechanism")
     *
     * @param Request $request
     * @param Mechanism $mechanism
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, Mechanism $mechanism, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * Redirect the slug is incorrect
         */
        if ($slug !== $mechanism->getSlug()) {
            return $this->redirect($this->generateUrl('mechanism_show', array(
                    'id' => $mechanism->getId(),
                    'slug' => $mechanism->getSlug(),
                )
            ));
        }

        /** @var JeuRepository $jeuReposititory */
        $jeuReposititory = $em->getRepository('JDJJeuBundle:Jeu');
        /** @var Pagerfanta $jeux */
        $jeux = $jeuReposititory->createPaginator(array("mechanism" => $mechanism));
        $jeux->setMaxPerPage(16);
        $jeux->setCurrentPage($request->get('page', 1));

        return $this->render('frontend/mechanism/show.html.twig', array(
            'entity' => $mechanism,
            'jeux' => $jeux,
        ));
    }
}