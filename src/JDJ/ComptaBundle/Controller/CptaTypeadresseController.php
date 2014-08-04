<?php

namespace JDJ\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\ComptaBundle\Entity\CptaTypeadresse;
use JDJ\ComptaBundle\Form\CptaTypeadresseType;

/**
 * CptaTypeadresse controller.
 *
 */
class CptaTypeadresseController extends Controller
{

    /**
     * Lists all CptaTypeadresse entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJComptaBundle:CptaTypeadresse')->findAll();

        return $this->render('JDJComptaBundle:CptaTypeadresse:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new CptaTypeadresse entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CptaTypeadresse();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cptatypeadresse_show', array('id' => $entity->getId())));
        }

        return $this->render('JDJComptaBundle:CptaTypeadresse:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CptaTypeadresse entity.
    *
    * @param CptaTypeadresse $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CptaTypeadresse $entity)
    {
        $form = $this->createForm(new CptaTypeadresseType(), $entity, array(
            'action' => $this->generateUrl('cptatypeadresse_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CptaTypeadresse entity.
     *
     */
    public function newAction()
    {
        $entity = new CptaTypeadresse();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJComptaBundle:CptaTypeadresse:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CptaTypeadresse entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaTypeadresse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaTypeadresse entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaTypeadresse:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CptaTypeadresse entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaTypeadresse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaTypeadresse entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaTypeadresse:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CptaTypeadresse entity.
    *
    * @param CptaTypeadresse $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CptaTypeadresse $entity)
    {
        $form = $this->createForm(new CptaTypeadresseType(), $entity, array(
            'action' => $this->generateUrl('cptatypeadresse_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CptaTypeadresse entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaTypeadresse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaTypeadresse entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cptatypeadresse_edit', array('id' => $id)));
        }

        return $this->render('JDJComptaBundle:CptaTypeadresse:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CptaTypeadresse entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJComptaBundle:CptaTypeadresse')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CptaTypeadresse entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cptatypeadresse'));
    }

    /**
     * Creates a form to delete a CptaTypeadresse entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cptatypeadresse_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
