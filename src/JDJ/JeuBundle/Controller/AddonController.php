<?php

namespace JDJ\JeuBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\JeuBundle\Entity\Addon;
use JDJ\JeuBundle\Form\AddonType;

/**
 * Addon controller.
 *
 */
class AddonController extends Controller
{

    /**
     * Lists all Addon entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJJeuBundle:Addon')->findAll();

        return $this->render('JDJJeuBundle:Addon:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Addon entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Addon();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('jeu_addon_show', array('id' => $entity->getId())));
        }

        return $this->render('JDJJeuBundle:Addon:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Addon entity.
     *
     * @param Addon $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Addon $entity)
    {
        $form = $this->createForm(new AddonType(), $entity, array(
            'action' => $this->generateUrl('jeu_addon_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Addon entity.
     *
     */
    public function newAction($jeu_id)
    {
        $entity = new Addon();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJJeuBundle:Addon:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Addon entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Addon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Addon entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJJeuBundle:Addon:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Addon entity.
     *
     */
    public function editAction($jeu_id, $id)
    {

        var_dump($jeu_id);
        var_dump($id);
        exit;
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Addon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Addon entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJJeuBundle:Addon:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Addon entity.
    *
    * @param Addon $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Addon $entity)
    {
        $form = $this->createForm(new AddonType(), $entity, array(
            'action' => $this->generateUrl('jeu_addon_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Addon entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Addon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Addon entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('jeu_addon_edit', array('id' => $id)));
        }

        return $this->render('JDJJeuBundle:Addon:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Addon entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJJeuBundle:Addon')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Addon entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('jeu_addon'));
    }

    /**
     * Creates a form to delete a Addon entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('jeu_addon_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
