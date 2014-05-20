<?php

namespace JDJ\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\ComptaBundle\Entity\CptaFacture;
use JDJ\ComptaBundle\Form\CptaFactureType;

/**
 * CptaFacture controller.
 *
 */
class CptaFactureController extends Controller
{

    /**
     * Lists all CptaFacture entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJComptaBundle:CptaFacture')->findAll();

        return $this->render('JDJComptaBundle:CptaFacture:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new CptaFacture entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CptaFacture();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cptafacture_show', array('id' => $entity->getId())));
        }

        return $this->render('JDJComptaBundle:CptaFacture:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CptaFacture entity.
    *
    * @param CptaFacture $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CptaFacture $entity)
    {
        $form = $this->createForm(new CptaFactureType(), $entity, array(
            'action' => $this->generateUrl('cptafacture_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CptaFacture entity.
     *
     */
    public function newAction()
    {
        $entity = new CptaFacture();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJComptaBundle:CptaFacture:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CptaFacture entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaFacture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaFacture entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaFacture:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CptaFacture entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaFacture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaFacture entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaFacture:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CptaFacture entity.
    *
    * @param CptaFacture $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CptaFacture $entity)
    {
        $form = $this->createForm(new CptaFactureType(), $entity, array(
            'action' => $this->generateUrl('cptafacture_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CptaFacture entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaFacture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaFacture entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cptafacture_edit', array('id' => $id)));
        }

        return $this->render('JDJComptaBundle:CptaFacture:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CptaFacture entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJComptaBundle:CptaFacture')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CptaFacture entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cptafacture'));
    }

    /**
     * Creates a form to delete a CptaFacture entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cptafacture_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
