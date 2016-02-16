<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 10/02/2016
 * Time: 18:08
 */

namespace AppBundle\Controller\Frontend;

use JDJ\CoreBundle\Entity\EntityRepository;
use JDJ\JeuBundle\Entity\Jeu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/user-review")
 */
class UserReviewController extends Controller
{
    /**
     * @Route("/", name="user_review_index")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array('createdAt' => 'desc'));
        $template = $request->get('template', 'index.html.twig');

        $userReviews = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setMaxPerPage($request->get('maxPerPage', 10))
            ->setCurrentPage($request->get('page', 1));

        return $this->render('frontend/game_review/'.$template, array(
            'game_reviews' => $userReviews,
        ));
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->get('app.repository.game_review');
    }
}