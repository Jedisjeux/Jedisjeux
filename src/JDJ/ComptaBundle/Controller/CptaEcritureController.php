<?php

namespace JDJ\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\ComptaBundle\Entity\CptaEcriture;
use JDJ\ComptaBundle\Form\CptaEcritureType;

/**
 * CptaEcriture controller.
 *
 */
class CptaEcritureController extends Controller
{

    /**
     * Lists all CptaEcriture entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJComptaBundle:CptaEcriture')->findAll();

        return $this->render('JDJComptaBundle:CptaEcriture:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new CptaEcriture entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CptaEcriture();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cptaecriture_show', array('id' => $entity->getId())));
        }

        return $this->render('JDJComptaBundle:CptaEcriture:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CptaEcriture entity.
    *
    * @param CptaEcriture $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CptaEcriture $entity)
    {
        $form = $this->createForm(new CptaEcritureType(), $entity, array(
            'action' => $this->generateUrl('cptaecriture_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CptaEcriture entity.
     *
     */
    public function newAction()
    {
        $entity = new CptaEcriture();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJComptaBundle:CptaEcriture:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CptaEcriture entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaEcriture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaEcriture entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaEcriture:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CptaEcriture entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaEcriture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaEcriture entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaEcriture:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CptaEcriture entity.
    *
    * @param CptaEcriture $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CptaEcriture $entity)
    {
        $form = $this->createForm(new CptaEcritureType(), $entity, array(
            'action' => $this->generateUrl('cptaecriture_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CptaEcriture entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaEcriture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaEcriture entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cptaecriture_edit', array('id' => $id)));
        }

        return $this->render('JDJComptaBundle:CptaEcriture:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CptaEcriture entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJComptaBundle:CptaEcriture')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CptaEcriture entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cptaecriture'));
    }

    /**
     * Creates a form to delete a CptaEcriture entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cptaecriture_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
