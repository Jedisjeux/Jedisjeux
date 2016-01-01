<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 21/12/2015
 * Time: 17:11
 */

namespace AppBundle\Controller\Backend;

use JDJ\JeuBundle\Entity\Jeu;
use JDJ\JeuBundle\Form\JeuType;
use JDJ\JeuBundle\Repository\JeuRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/jeu")
 */
class GameController extends Controller
{
    /**
     * @Route("/", name="admin_game_index")
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array('createdAt' => 'desc'));

        $games = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setCurrentPage($request->get('page', 1));

        return $this->render('backend/game/index.html.twig', array(
            'games' => $games,
        ));
    }

    /**
     * @Route("/new", name="admin_game_new")
     *
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $game = new Jeu();
        $form = $this->createForm(new JeuType(), $game);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'admin_game_index',
                array('id' => $game->getId())
            ));
        }

        return $this->render('backend/game/new.html.twig', array(
            'game' => $game,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="admin_game_edit")
     *
     * @ParamConverter("game", class="JDJJeuBundle:Jeu")
     *
     * @param Request $request
     * @param Jeu $game
     *
     * @return array
     */
    public function updateAction(Request $request, Jeu $game)
    {
        $form = $this->createForm(new JeuType(), $game);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'admin_game_index',
                array('id' => $game->getId())
            ));
        }

        return $this->render('backend/game/edit.html.twig', array(
            'game' => $game,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="admin_game_delete")
     *
     * @ParamConverter("game", class="JDJJeuBundle:Jeu")
     *
     * @param Jeu $game
     *
     * @return RedirectResponse
     */
    public function deleteAction(Jeu $game)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($game);
        $em->flush();

        return $this->redirect($this->generateUrl(
            'admin_game_index'
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