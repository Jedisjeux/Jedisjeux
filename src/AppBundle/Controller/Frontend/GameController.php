<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/02/2016
 * Time: 12:46
 */

namespace AppBundle\Controller\Frontend;

use JDJ\JeuBundle\Repository\JeuRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/jeu")
 */
class GameController extends Controller
{
    /**
     * @Route("/_partial", name="game_index")
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array('createdAt' => 'desc'));
        $template = $request->get('template', 'index.html.twig');

        $games = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setMaxPerPage($request->get('maxPerPage', 10))
            ->setCurrentPage($request->get('page', 1));

        return $this->render('frontend/game/'.$template, array(
            'games' => $games,
        ));
    }

    /**
     * @return JeuRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('JDJJeuBundle:Jeu');
    }
}