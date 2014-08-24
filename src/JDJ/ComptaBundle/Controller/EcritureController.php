<?php

namespace JDJ\ComptaBundle\Controller;

use JDJ\ComptaBundle\Entity\Ecriture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\ComptaBundle\Form\EcritureType;

/**
 * Ecriture controller.
 *
 */
class EcritureController extends Controller
{

    /**
     * Lists all Ecriture entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJComptaBundle:Ecriture')->findAll();

        return $this->render('JDJComptaBundle:Ecriture:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Ecriture entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Ecriture();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ecriture_show', array('id' => $entity->getId())));
        }

        return $this->render('JDJComptaBundle:Ecriture:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Ecriture entity.
    *
    * @param Ecriture $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Ecriture $entity)
    {
        $form = $this->createForm(new EcritureType(), $entity, array(
            'action' => $this->generateUrl('ecriture_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Ecriture entity.
     *
     */
    public function newAction()
    {
        $entity = new Ecriture();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJComptaBundle:Ecriture:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Ecriture entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:Ecriture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ecriture entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:Ecriture:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Ecriture entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:Ecriture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ecriture entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:Ecriture:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Ecriture entity.
    *
    * @param Ecriture $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ecriture $entity)
    {
        $form = $this->createForm(new EcritureType(), $entity, array(
            'action' => $this->generateUrl('ecriture_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    /**
     * Edits an existing Ecriture entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:Ecriture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ecriture entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ecriture_edit', array('id' => $id)));
        }

        return $this->render('JDJComptaBundle:Ecriture:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Ecriture entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJComptaBundle:Ecriture')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ecriture entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ecriture'));
    }

    /**
     * Creates a form to delete a Ecriture entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ecriture_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
