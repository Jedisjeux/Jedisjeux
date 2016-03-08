<?php

namespace JDJ\CollectionBundle\Controller;

use Doctrine\Common\Util\Debug;
use JDJ\CollectionBundle\Service\UserGameAttributeService;
use JDJ\UserBundle\Entity\User;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class UserGameAttributeController
 *
 * @package JDJ\CollectionBundle\Controller
 * @Route("/usergameattribute")
 */
class UserGameAttributeController extends Controller
{

    /**
     * handle the click on favorite for a game
     *
     * @Route("/favorite/{jeu}/{user}", name="usergameattribute_favorite", options={"expose"=true})
     * @ParamConverter("jeu", class="AppBundle:Product")
     * @ParamConverter("user", class="JDJUserBundle:User")
     */
    public function favoriteAction(ProductInterface $jeu, User $user)
    {

        $this
            ->getUserGameAttributeService()
            ->favorite($jeu, $user);

        return new Response(Response::HTTP_OK);
    }


    /**
     * handle the click on owned for a game
     *
     * @Route("/owned/{jeu}/{user}", name="usergameattribute_owned", options={"expose"=true})
     * @ParamConverter("jeu", class="AppBundle:Product")
     * @ParamConverter("user", class="JDJUserBundle:User")
     */
    public function ownedAction(ProductInterface $jeu, User $user)
    {
        $this
            ->getUserGameAttributeService()
            ->owned($jeu, $user);

        return new Response(Response::HTTP_OK);
    }

    /**
     * handle the click on wanted for a game
     *
     * @Route("/wanted/{jeu}/{user}", name="usergameattribute_wanted", options={"expose"=true})
     * @ParamConverter("jeu", class="AppBundle:Product")
     * @ParamConverter("user", class="JDJUserBundle:User")
     */
    public function wantedAction(ProductInterface $jeu, User $user)
    {
        $this
            ->getUserGameAttributeService()
            ->wanted($jeu, $user);

        return new Response(Response::HTTP_OK);
    }

    /**
     * handle the click on played for a game
     *
     * @Route("/played/{jeu}/{user}", name="usergameattribute_played", options={"expose"=true})
     * @ParamConverter("jeu", class="AppBundle:Product")
     * @ParamConverter("user", class="JDJUserBundle:User")
     */
    public function playedAction(ProductInterface $jeu, User $user)
    {
        $this
            ->getUserGameAttributeService()
            ->played($jeu, $user);

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
