<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/01/2016
 * Time: 17:29
 */

namespace AppBundle\Controller\Backend;

use FOS\UserBundle\Doctrine\UserManager;
use JDJ\UserBundle\Entity\User;
use JDJ\UserBundle\Form\UserType;
use JDJ\UserBundle\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="admin_user_index")
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array('name' => 'asc'));

        $users = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setCurrentPage($request->get('page', 1));

        return $this->render('backend/user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * @Route("/new", name="admin_user_new")
     *
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUserManager()->createUser();
        $form = $this->createForm(new UserType(), $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getAvatar()) {
                $user->getAvatar()->upload();
            }
            $this->getUserManager()->updateUser($user);

            return $this->redirect($this->generateUrl(
                'admin_user_index',
                array('id' => $user->getId())
            ));
        }

        return $this->render('backend/user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit")
     *
     * @ParamConverter("user", class="JDJJeuBundle:User")
     *
     * @param Request $request
     * @param User $user
     *
     * @return array
     */
    public function updateAction(Request $request, User $user)
    {
        $form = $this->createForm(new UserType(), $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getAvatar()) {
                $user->getAvatar()->upload();
            }
            $this->getUserManager()->updateUser($user);

            return $this->redirect($this->generateUrl(
                'admin_user_index',
                array('id' => $user->getId())
            ));
        }

        return $this->render('backend/user/edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="admin_user_delete")
     *
     * @ParamConverter("user", class="JDJJeuBundle:User")
     *
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function deleteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirect($this->generateUrl(
            'admin_user_index'
        ));
    }

    /**
     * @return UserRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('JDJJeuBundle:User');
    }

    /**
     * @return UserManager
     */
    public function getUserManager()
    {
        return $this->get("fos_user.user_manager");
    }
}