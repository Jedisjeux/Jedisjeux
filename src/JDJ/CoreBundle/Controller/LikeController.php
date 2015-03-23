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
     * @param Request $request
     * @param $userReview
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function likeUserReviewAction(Request $request, UserReview $userReview)
    {
        if ($this->getUser() === $userReview->getJeuNote()->getAuthor()) {
            throw new Exception("utilisateur identique à la critique");
        }

        $em = $this->getDoctrine()->getManager();
        $like = $this->getLike('userReview', $userReview, $request);

        $like
            ->setUserReview($userReview);

        $em->flush();

        return new JsonResponse(array(
            'nbLikes' => $userReview->getNbLikes(),
            'nbUnLikes' => $userReview->getNbUnlikes(),
        ));
    }

    private function getLike($entityName, $entity, Request $request)
    {
        $like = $this
            ->getDoctrine()
            ->getRepository('JDJCoreBundle:Like')
            ->findOneBy(array(
                'createdBy' => $this->getUser(),
                $entityName => $entity,
            ));

        if (null === $like) {
            $like = new Like();
            $like
                ->setCreatedBy($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($like);
        }

        $like->setLike((bool) $request->request->get('like'));

        return $like;
    }
}