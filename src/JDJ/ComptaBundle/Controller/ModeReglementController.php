<?php

namespace JDJ\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\ComptaBundle\Entity\ModeReglement;
use JDJ\ComptaBundle\Form\ModeReglementType;

/**
 * ModeReglement controller.
 *
 */
class ModeReglementController extends Controller
{

    /**
     * Lists all ModeReglement entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJComptaBundle:ModeReglement')->findAll();

        $deleteForms = array();
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('JDJComptaBundle:ModeReglement:index.html.twig', array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
        ));
    }
    /**
     * Creates a new ModeReglement entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ModeReglement();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('modereglement'));
        }

        return $this->render('JDJComptaBundle:ModeReglement:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a ModeReglement entity.
    *
    * @param ModeReglement $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(ModeReglement $entity)
    {
        $form = $this->createForm(new ModeReglementType(), $entity, array(
            'action' => $this->generateUrl('modereglement_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new ModeReglement entity.
     *
     */
    public function newAction()
    {
        $entity = new ModeReglement();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJComptaBundle:ModeReglement:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ModeReglement entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:ModeReglement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ModeReglement entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:ModeReglement:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing ModeReglement entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:ModeReglement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ModeReglement entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('JDJComptaBundle:ModeReglement:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to edit a ModeReglement entity.
    *
    * @param ModeReglement $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ModeReglement $entity)
    {
        $form = $this->createForm(new ModeReglementType(), $entity, array(
            'action' => $this->generateUrl('modereglement_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    /**
     * Edits an existing ModeReglement entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:ModeReglement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ModeReglement entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('modereglement'));
        }

        return $this->render('JDJComptaBundle:ModeReglement:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ModeReglement entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJComptaBundle:ModeReglement')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ModeReglement entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('modereglement'));
    }

    /**
     * Creates a form to delete a ModeReglement entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('modereglement_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
