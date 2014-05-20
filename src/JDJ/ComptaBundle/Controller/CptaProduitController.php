<?php

namespace JDJ\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\ComptaBundle\Entity\CptaProduit;
use JDJ\ComptaBundle\Form\CptaProduitType;

/**
 * CptaProduit controller.
 *
 */
class CptaProduitController extends Controller
{

    /**
     * Lists all CptaProduit entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJComptaBundle:CptaProduit')->findAll();

        return $this->render('JDJComptaBundle:CptaProduit:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new CptaProduit entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CptaProduit();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cptaproduit_show', array('id' => $entity->getId())));
        }

        return $this->render('JDJComptaBundle:CptaProduit:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CptaProduit entity.
    *
    * @param CptaProduit $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CptaProduit $entity)
    {
        $form = $this->createForm(new CptaProduitType(), $entity, array(
            'action' => $this->generateUrl('cptaproduit_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CptaProduit entity.
     *
     */
    public function newAction()
    {
        $entity = new CptaProduit();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJComptaBundle:CptaProduit:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CptaProduit entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaProduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaProduit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaProduit:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CptaProduit entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaProduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaProduit entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaProduit:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CptaProduit entity.
    *
    * @param CptaProduit $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CptaProduit $entity)
    {
        $form = $this->createForm(new CptaProduitType(), $entity, array(
            'action' => $this->generateUrl('cptaproduit_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CptaProduit entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaProduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaProduit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cptaproduit_edit', array('id' => $id)));
        }

        return $this->render('JDJComptaBundle:CptaProduit:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CptaProduit entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJComptaBundle:CptaProduit')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CptaProduit entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cptaproduit'));
    }

    /**
     * Creates a form to delete a CptaProduit entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cptaproduit_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
