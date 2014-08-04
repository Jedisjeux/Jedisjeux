<?php

namespace JDJ\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\ComptaBundle\Entity\CptaTarifproduit;
use JDJ\ComptaBundle\Form\CptaTarifproduitType;

/**
 * CptaTarifproduit controller.
 *
 */
class CptaTarifproduitController extends Controller
{

    /**
     * Lists all CptaTarifproduit entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJComptaBundle:CptaTarifproduit')->findAll();

        return $this->render('JDJComptaBundle:CptaTarifproduit:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new CptaTarifproduit entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CptaTarifproduit();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cptatarifproduit_show', array('id' => $entity->getId())));
        }

        return $this->render('JDJComptaBundle:CptaTarifproduit:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CptaTarifproduit entity.
    *
    * @param CptaTarifproduit $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CptaTarifproduit $entity)
    {
        $form = $this->createForm(new CptaTarifproduitType(), $entity, array(
            'action' => $this->generateUrl('cptatarifproduit_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CptaTarifproduit entity.
     *
     */
    public function newAction()
    {
        $entity = new CptaTarifproduit();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJComptaBundle:CptaTarifproduit:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CptaTarifproduit entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaTarifproduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaTarifproduit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaTarifproduit:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CptaTarifproduit entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaTarifproduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaTarifproduit entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaTarifproduit:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CptaTarifproduit entity.
    *
    * @param CptaTarifproduit $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CptaTarifproduit $entity)
    {
        $form = $this->createForm(new CptaTarifproduitType(), $entity, array(
            'action' => $this->generateUrl('cptatarifproduit_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CptaTarifproduit entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaTarifproduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaTarifproduit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cptatarifproduit_edit', array('id' => $id)));
        }

        return $this->render('JDJComptaBundle:CptaTarifproduit:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CptaTarifproduit entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJComptaBundle:CptaTarifproduit')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CptaTarifproduit entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cptatarifproduit'));
    }

    /**
     * Creates a form to delete a CptaTarifproduit entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cptatarifproduit_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
