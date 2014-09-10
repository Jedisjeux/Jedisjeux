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
use JDJ\UserReviewBundle\Entity\UserReviewRepository;
use Pagerfanta\Pagerfanta;

class UserReviewController extends Controller
{
    /**
     * Lists all UserReview entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * Find All User Review entities from this game
         */
        /** @var UserReviewRepository $userReviewReposititory */
        $userReviewReposititory = $em->getRepository('JDJUserReviewBundle:UserReview');
        /** @var PagerFanta $entities */
        $entities = $userReviewReposititory->createPaginator();
        $entities->setMaxPerPage(10);
        $entities->setCurrentPage($request->get('page', 1));

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
            $jeu = $form->get('jeuNote')->get('jeu')->getData();
            $jeuNote = $this->findJeuNote($jeu);
            if (null !== $jeuNote) {
                $entity->setJeuNote($jeuNote);
            }
            $entity
                ->getJeuNote()
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
    protected function findJeu($idJeu)
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
     * @return mixed
     */
    protected function findJeuNote(Jeu $jeu)
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('JDJUserReviewBundle:JeuNote')->findOneBy(array(
            'jeu' => $jeu,
            'author'=> $this->getUser(),
        ));
    }

    /**
     * Displays a form to create a new UserReview entity.
     *
     */
    public function newAction($idJeu)
    {
        $jeu = $this->findJeu($idJeu);

        $jeuNote = $this->findJeuNote($jeu);
        $userReview = null;

        if (null !== $jeuNote) {
            $userReview = $jeuNote->getUserReview();
        }

        /**
         * The User Review already exists
         * Redirect to the edit route
         */
        if (null !== $userReview) {
            return $this->redirect($this->generateUrl('user_review_edit', array(
                'id' => $userReview->getId(),
            )));
        }

        $entity = new UserReview();
        $form   = $this->createCreateForm($entity);

        /**
         * Pre populate data
         */
        if (null !== $jeuNote) {
            $form->get('jeuNote')->setData($jeuNote);
        } else {
            $form->get('jeuNote')->get('jeu')->setData($jeu);
        }

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