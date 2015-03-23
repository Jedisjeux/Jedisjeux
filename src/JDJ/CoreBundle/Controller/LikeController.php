<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/03/15
 * Time: 22:24
 */

namespace JDJ\CoreBundle\Controller;


use JDJ\CoreBundle\Entity\Like;
use JDJ\CoreBundle\Form\LikeType;
use JDJ\UserReviewBundle\Entity\UserReview;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class LikeController
 * @package JDJ\UserReviewBundle\Controller
 */
class LikeController extends Controller
{
    /**
     * @Route("/user-review/{id}/like", name="user_review_like", options={"expose"=true})
     * @ParamConverter("userReview", class="JDJUserReviewBundle:UserReview")
     *
     * @param UserReview $userReview
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function likeUserReviewAction(UserReview $userReview, Request $request)
    {
        if ($this->getUser() === $userReview->getJeuNote()->getAuthor()) {
            throw new Exception("utilisateur identique à la critique");
        }

        $like = $this->findLike('userReview', $userReview);

        $isNew = false;
        if (null === $like) {
            $like = new Like();
            $like
                ->setUserReview($userReview)
                ->setCreatedBy($this->getUser());
            $isNew = true;
        }

        $form = $this->createLikeForm($like, $this->generateUrl('user_review_like', array(
            'id' => $userReview->getId()
        )));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($isNew) {
                $em->persist($like);
            }
            $em->flush();

            return new JsonResponse(array(
                'nbLikes' => $userReview->getNbLikes(),
                'nbUnlikes' => $userReview->getNbUnlikes(),
            ));
        }

        return $this->render('like/user-review.html.twig', array(
            'form' => $form->createView(),
            'like' => $like,
        ));
    }

    /**
     * Creates a form to create or update a Like entity.
     *
     * @param Like $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createLikeForm(Like $entity, $action)
    {
        $form = $this->createForm(new LikeType(), $entity, array(
            'action' => $action,
            'method' => 'POST',
        ));

        return $form;
    }


    private function findLike($entityName, $entity)
    {
        $like = $this
            ->getDoctrine()
            ->getRepository('JDJCoreBundle:Like')
            ->findOneBy(array(
                'createdBy' => $this->getUser(),
                $entityName => $entity,
            ));

        return $like;
    }

}