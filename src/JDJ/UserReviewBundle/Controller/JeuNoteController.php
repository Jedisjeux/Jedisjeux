<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 31/08/2014
 * Time: 18:02
 */

namespace JDJ\UserReviewBundle\Controller;


use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserReviewBundle\Entity\JeuNote;
use JDJ\UserReviewBundle\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class JeuNoteController extends Controller
{
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
     * @param $idNote
     * @return Note
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function findNote($idNote)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Note $note */

        $note = $em->getRepository('JDJUserReviewBundle:Note')->find($idNote);

        if (!$note) {
            throw $this->createNotFoundException('Unable to find Note entity.');
        }

        return $note;
    }

    /**
     * Creates a new JeuNote entity.
     *
     */
    public function createAction(Request $request)
    {
        $jeu = $this->findJeu($request->request->get("idJeu"));
        $note = $this->findNote($request->request->get("idNote"));

        /**
         * Find if a note already exists
         * for logged user
         */
        $entity = $this->findJeuNote($jeu);

        if (!$entity) {
            $entity = new JeuNote();
        }

        $entity
            ->setJeu($jeu)
            ->setNote($note)
            ->setAuthor($this->getUser())
        ;

        $em = $this->getDoctrine()->getManager();

        $em->persist($entity);
        $em->flush();

        return new JsonResponse(array(
            'valeur' => $entity->getNote()->getValeur(),
        ));
    }

    /**
     * Displays a form to create a new JeuNote entity.
     *
     */
    public function newAction($idJeu)
    {
        $jeu = $this->findJeu($idJeu);
        $em = $this->getDoctrine()->getManager();
        $userNote = $em->getRepository('JDJUserReviewBundle:JeuNote')->findOneBy(array(
            'jeu' => $jeu,
            'author' => $this->getUser(),
        ));
        $notes = $em->getRepository('JDJUserReviewBundle:Note')->findAll();

        $deleteForm = null;
        if (null !== $userNote) {
            $deleteForm = $this->createDeleteForm($userNote->getId())->createView();
        }


        return $this->render('JDJUserReviewBundle:JeuNote:new.html.twig', array(
            'userNote' => $userNote,
            'notes' => $notes,
            'jeu' => $jeu,
            'delete_form' => $deleteForm,
        ));
    }

    /**
     * Deletes a JeuNote entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var JeuNote $entity */
            $entity = $em->getRepository('JDJUserReviewBundle:JeuNote')->find($id);


            if (!$entity) {
                throw $this->createNotFoundException('Unable to find JeuNote entity.');
            }

            /**
             * Remove linked UserReview entity
             */
            $em->remove($entity->getUserReview());
            /**
             * Remove JeuNote entity
             */
            $em->remove($entity);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'L\'avis a été supprimé');
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
            ->setAction($this->generateUrl('user_jeu_note_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
            ;
    }
} 