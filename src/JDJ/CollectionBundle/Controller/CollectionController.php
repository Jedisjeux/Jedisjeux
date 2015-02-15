<?php

namespace JDJ\CollectionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\CollectionBundle\Entity\Collection;
use JDJ\CollectionBundle\Form\CollectionType;

/**
 * Collection controller.
 *
 */
class CollectionController extends Controller
{


    /**
     * Lists all Collection entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $collections = $em->getRepository('JDJCollectionBundle:Collection')->findAll();

        return $this->render('JDJCollectionBundle:Collection:index.html.twig', array(
            'entities' => $collections,
        ));
    }

    /**
     * Creates a new Collection entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Collection();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('collection_show', array('id' => $entity->getId())));
        }

        return $this->render('JDJCollectionBundle:Collection:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Collection entity.
     *
     * @param Collection $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Collection $entity)
    {
        $form = $this->createForm(new CollectionType(), $entity, array(
            'action' => $this->generateUrl('collection_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Collection entity.
     *
     */
    public function newAction()
    {
        $entity = new Collection();
        $form = $this->createCreateForm($entity);

        return $this->render('JDJCollectionBundle:Collection:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Collection entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('JDJCollectionBundle:Collection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Collection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJCollectionBundle:Collection:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Collection entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJCollectionBundle:Collection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Collection entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJCollectionBundle:Collection:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Collection entity.
     *
     * @param Collection $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Collection $entity)
    {
        $form = $this->createForm(new CollectionType(), $entity, array(
            'action' => $this->generateUrl('collection_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Collection entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJCollectionBundle:Collection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Collection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('collection_edit', array('id' => $id)));
        }

        return $this->render('JDJCollectionBundle:Collection:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Collection entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJCollectionBundle:Collection')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Collection entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('collection'));
    }

    /**
     * Creates a form to delete a Collection entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('collection_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
