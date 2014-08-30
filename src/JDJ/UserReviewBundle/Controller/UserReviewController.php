<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 30/08/2014
 * Time: 17:11
 */

namespace JDJ\UserReviewBundle\Controller;

use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserReviewBundle\Entity\UserReview;
use JDJ\UserReviewBundle\Form\UserReviewType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserReviewController extends Controller
{
    /**
     * Lists all UserReview entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJUserReviewBundle:UserReview')->findBy(array(
            'commented' => true,
        ));

        $deleteForms = array();
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }


        return $this->render('JDJUserReviewBundle:UserReview:index.html.twig', array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
        ));
    }
    /**
     * Creates a new UserReview entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new UserReview();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity
                ->setCommented(true)
                ->setAuthor($this->getUser())
            ;
            $em->persist($entity);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'L\'avis a bien été enregistré');
            return $this->redirect($this->generateUrl('user_review_show', array(
                'id' => $entity->getId(),
            )));
        }

        return $this->render('JDJUserReviewBundle:UserReview:edit.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a UserReview entity.
     *
     * @param UserReview $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(UserReview $entity)
    {
       $form = $this->createForm(new UserReviewType(), $entity, array(
            'action' => $this->generateUrl('user_review_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Find Jeu Entity
     *
     * @param $idJeu
     * @return Jeu
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function findJeu($idJeu)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Jeu $jeu */
        $jeu = $em->getRepository('JDJJeuBundle:Jeu')->find($idJeu);

        if (!$jeu) {
            throw $this->createNotFoundException('Unable to find Jeu entity.');
        }

        return $jeu;
    }

    /**
     * @param Jeu $jeu
     * @return UserReview
     */
    private function findUserReview(Jeu $jeu)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var UserReview $userReview */
        $userReview = $em->getRepository('JDJUserReviewBundle:UserReview')->findOneBy(array(
            'jeu' => $jeu,
            'author' => $this->getUser(),
        ));

        return $userReview;
    }

    /**
     * Displays a form to create a new UserReview entity.
     *
     */
    public function newAction($idJeu)
    {
        $jeu = $this->findJeu($idJeu);

        $userReview = $this->findUserReview($jeu);

        if ($userReview) {
            return $this->redirect($this->generateUrl('user_review_edit', array(
                'id' => $userReview->getId(),
            )));
        }

        $entity = new UserReview();
        $form   = $this->createCreateForm($entity);
        $form->get('jeu')->setData($jeu);

        return $this->render('JDJUserReviewBundle:UserReview:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserReview entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJUserReviewBundle:UserReview')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserReview entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJUserReviewBundle:UserReview:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing UserReview entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('JDJUserReviewBundle:UserReview')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserReview entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJUserReviewBundle:UserReview:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a UserReview entity.
     *
     * @param UserReview $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(UserReview $entity)
    {
        $form = $this->createForm(new UserReviewType(), $entity, array(
            'action' => $this->generateUrl('user_review_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Edits an existing UserReview entity.
     *
     */
    public function updateAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('JDJUserReviewBundle:UserReview')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserReview entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);


        if ($editForm->isValid()) {
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Vos modifications ont bien été enregistrées!');
            return $this->redirect($this->generateUrl('user_review_show', array(
                'id' => $entity->getId(),
            )));
        }

        return $this->render('JDJUserReviewBundle:UserReview:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a UserReview entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJUserReviewBundle:UserReview')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find UserReview entity.');
            }
            $request->getSession()->getFlashBag()->add('success', 'Le client a été supprimé');

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user_review'));
    }

    /**
     * Creates a form to delete a UserReview entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_review_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
            ;
    }
} 