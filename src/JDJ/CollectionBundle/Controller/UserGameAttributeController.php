<?php

namespace JDJ\CollectionBundle\Controller;

use Doctrine\Common\Util\Debug;
use JDJ\CollectionBundle\Entity\UserGameAttribute;
use JDJ\CollectionBundle\Form\UserGameAttributeType;
use JDJ\CollectionBundle\Service\UserGameAttributeService;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\CollectionBundle\Entity\Collection;
use JDJ\CollectionBundle\Form\CollectionType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Collection controller.
 *
 */
class UserGameAttributeController extends Controller
{

    /**
     * handle the click on favorite for a game
     *
     */
    public function favoriteAction($jeu_id, $user_id)
    {
        $this
            ->getUserGameAttributeService()
            ->favorite($jeu_id, $user_id);

        return new Response(Response::HTTP_OK);
    }


    /**
     * handle the click on owned for a game
     *
     */
    public function ownedAction($jeu_id, $user_id)
    {
        $this
            ->getUserGameAttributeService()
            ->owned($jeu_id, $user_id);

        return new Response(Response::HTTP_OK);
    }

    /**
     * handle the click on wanted for a game
     *
     */
    public function wantedAction($jeu_id, $user_id)
    {
        $this
            ->getUserGameAttributeService()
            ->wanted($jeu_id, $user_id);

        return new Response(Response::HTTP_OK);
    }

    /**
     * handle the click on played for a game
     *
     */
    public function playedAction($jeu_id, $user_id)
    {
        $this
            ->getUserGameAttributeService()
            ->played($jeu_id, $user_id);

        return new Response(Response::HTTP_OK);
    }

    /**
     * @return UserGameAttributeService
     */
    private function getUserGameAttributeService()
    {
        return $this->get("app.service.user.game.attribute");
    }


}
