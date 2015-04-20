<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/03/15
 * Time: 22:24
 */

namespace JDJ\CoreBundle\Controller;


use JDJ\CommentBundle\Entity\Comment;
use JDJ\CoreBundle\Entity\Like;
use JDJ\CoreBundle\Form\LikeType;
use JDJ\CoreBundle\Repository\LikeRepository;
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
     * @return LikeRepository
     */
    private function getLikeRepository()
    {
        return $this->getDoctrine()->getRepository('JDJCoreBundle:Like');
    }

    /**
     * Like or Dislike a User Review
     *
     * @Route("/user-review/{id}/like", name="user_review_like", options={"expose"=true})
     * @ParamConverter("userReview", class="JDJUserReviewBundle:UserReview")
     *
     * @param UserReview $userReview
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function likeOrDislikeUserReviewAction(UserReview $userReview, Request $request)
    {
        if ($this->getUser() === $userReview->getJeuNote()->getAuthor()) {
            throw new Exception("utilisateur identique à la critique");
        }

        $like = null;

        if ($this->getUser()) {
            $like = $this
                ->getLikeRepository()
                ->findUserLikeOnEntity($this->getUser(), 'userReview', $userReview);
        }

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
                'nbDislikes' => $userReview->getNbDislikes(),
            ));
        }

        return $this->render('like/user-review.html.twig', array(
            'form' => $form->createView(),
            'like' => $like,
        ));
    }


    /**
     * Like or Dislike a Comment
     *
     * @Route("/comment/{id}/like", name="comment_like", options={"expose"=true})
     * @ParamConverter("comment", class="JDJCommentBundle:Comment")
     *
     * @param Comment $comment
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function likeOrDislikeCommentAction(Comment $comment, Request $request)
    {
        if ($this->getUser() === $comment->getAuthor()) {
            throw new Exception("utilisateur identique à la critique");
        }

        $like = null;

        if ($this->getUser()) {
            $like = $this
                ->getLikeRepository()
                ->findUserLikeOnEntity($this->getUser(), 'comment', $comment);
        }

        $isNew = false;
        if (null === $like) {
            $like = new Like();
            $like
                ->setComment($comment)
                ->setCreatedBy($this->getUser());
            $isNew = true;
        }

        $form = $this->createLikeForm($like, $this->generateUrl('comment_like', array(
            'id' => $comment->getId()
        )));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($isNew) {
                $em->persist($like);
            }
            $em->flush();

            return new JsonResponse(array(
                'nbLikes' => $comment->getNbLikes(),
                'nbDislikes' => $comment->getNbDislikes(),
            ));
        }

        return $this->render('like/comment.html.twig', array(
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

}