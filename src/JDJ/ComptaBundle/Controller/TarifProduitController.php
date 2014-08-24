<?php

namespace JDJ\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\ComptaBundle\Entity\Tarifproduit;
use JDJ\ComptaBundle\Form\TarifproduitType;

/**
 * Tarifproduit controller.
 *
 */
class TarifproduitController extends Controller
{

    /**
     * Lists all Tarifproduit entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJComptaBundle:Tarifproduit')->findAll();

        return $this->render('JDJComptaBundle:Tarifproduit:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Tarifproduit entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Tarifproduit();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tarifproduit'));
        }

        return $this->render('JDJComptaBundle:Tarifproduit:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Tarifproduit entity.
    *
    * @param Tarifproduit $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Tarifproduit $entity)
    {
        $form = $this->createForm(new TarifproduitType(), $entity, array(
            'action' => $this->generateUrl('tarifproduit_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Tarifproduit entity.
     *
     */
    public function newAction()
    {
        $entity = new Tarifproduit();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJComptaBundle:Tarifproduit:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Tarifproduit entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:Tarifproduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tarifproduit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:Tarifproduit:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Tarifproduit entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:Tarifproduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tarifproduit entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('JDJComptaBundle:Tarifproduit:edit.html.twig', array(
            'entity'    => $entity,
            'form'      => $form->createView(),
        ));
    }

    /**
    * Creates a form to edit a Tarifproduit entity.
    *
    * @param Tarifproduit $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tarifproduit $entity)
    {
        $form = $this->createForm(new TarifproduitType(), $entity, array(
            'action' => $this->generateUrl('tarifproduit_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Tarifproduit entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:Tarifproduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tarifproduit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tarifproduit'));
        }

        return $this->render('JDJComptaBundle:Tarifproduit:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Tarifproduit entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJComptaBundle:Tarifproduit')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tarifproduit entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tarifproduit'));
    }

    /**
     * Creates a form to delete a Tarifproduit entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tarifproduit_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
