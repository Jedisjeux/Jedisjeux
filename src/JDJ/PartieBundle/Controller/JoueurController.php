<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 30/08/2014
 * Time: 11:12
 */

namespace JDJ\PartieBundle\Controller;


use JDJ\PartieBundle\Entity\Joueur;
use JDJ\PartieBundle\Entity\Partie;
use JDJ\PartieBundle\Form\JoueurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JoueurController extends Controller
{
    /**
     * Lists all Joueur entities.
     *
     */
    public function indexAction(Request $request, $idPartie)
    {
        $em = $this->getDoctrine()->getManager();

        $partie = $this->findPartie($idPartie);
        $entities = $em->getRepository('JDJPartieBundle:Joueur')->findBy(array(
            'partie' => $partie,
        ));

        $deleteForms = array();
        /** @var Joueur $entity */
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('partie/joueur/index.html.twig', array(
            'entities' => $entities,
            'partie' => $partie,
            'deleteForms' => $deleteForms,
        ));
    }


    /**
     * Displays a form to create a new Client entity.
     *
     */
    public function newAction(Request $request, $idPartie)
    {
        $partie = $this->findPartie($idPartie);

        $entity = new Joueur();
        $form   = $this->createCreateForm($entity);

        /**
         * Pre populate data
         */
        $form->get('partie')->setData($partie);

        return $this->render('partie/joueur/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @param $idPartie
     * @return Partie
     */
    private function findPartie($idPartie)
    {
        $em = $this->getDoctrine()->getManager();
        $partie = $em->getRepository('JDJPartieBundle:Partie')->find((int)$idPartie);

        if (!$partie) {
            throw $this->createNotFoundException('Unable to find Partie entity.');
        }

        return $partie;
    }

    /**
     * Creates a new Partie entity.
     */
    public function createAction(Request $request)
    {
        $entity = new Joueur();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le joueur a bien été enregistré');
            return $this->redirect($this->generateUrl('partie_show', array(
                'id' => $entity->getPartie()->getId(),
                'slug' => $entity->getPartie()->getJeu()->getSlug(),
            )));
        }

        return $this->render('partie/joueur/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Joueur entity.
     *
     * @param Joueur $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Joueur $entity)
    {
        $form = $this->createForm(new JoueurType(), $entity, array(
            'action' => $this->generateUrl('joueur_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to edit an existing Joueur entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('JDJPartieBundle:Joueur')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Joueur entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('partie/joueur/edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Joueur entity.
     *
     * @param Joueur $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Joueur $entity)
    {
        $form = $this->createForm(new JoueurType(), $entity, array(
            'action' => $this->generateUrl('joueur_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Edits an existing Joueur entity.
     *
     */
    public function updateAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        /** @var Joueur $entity */
        $entity = $em->getRepository('JDJPartieBundle:Joueur')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Joueur entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);


        if ($editForm->isValid()) {
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Vos modifications ont bien été enregistrées!');
            return $this->redirect($this->generateUrl('partie_show', array(
                'id' => $entity->getPartie()->getId(),
                'slug' => $entity->getPartie()->getJeu()->getSlug(),
            )));
        }

        return $this->render('partie/joueur/edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to delete a Joueur entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('joueur_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * Deletes a Joueur entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJPartieBundle:Joueur')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Joueur entity.');
            }
            $request->getSession()->getFlashBag()->add('success', 'Le Joueur a été supprimé');

            $em->remove($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('partie_show', array(
                'id' => $entity->getPartie()->getId(),
                'slug' => $entity->getPartie()->getJeu()->getSlug(),
            )));
        }


    }
} 