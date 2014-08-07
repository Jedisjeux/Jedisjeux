<?php

namespace JDJ\JeuBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\JeuBundle\Entity\TypeAddon;
use JDJ\JeuBundle\Form\TypeAddonType;

/**
 * TypeAddon controller.
 *
 */
class TypeAddonController extends Controller
{

    /**
     * Lists all TypeAddon entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJJeuBundle:TypeAddon')->findAll();

        //Préparation du formulaire de delete
        $deleteForms = array();
        foreach ($entities as $typeAddon) {
            $deleteForms[$typeAddon->getId()] = $this->createDeleteForm($typeAddon->getId())->createView();
        }

        return $this->render('JDJJeuBundle:TypeAddon:index.html.twig', array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
        ));
    }
    /**
     * Creates a new TypeAddon entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new TypeAddon();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le type d\'addon a bien été enregistré');
            return $this->redirect($this->generateUrl('typeAddon', array('id' => $entity->getId())));
        }

        return $this->render('JDJJeuBundle:TypeAddon:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a TypeAddon entity.
     *
     * @param TypeAddon $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TypeAddon $entity)
    {
        $form = $this->createForm(new TypeAddonType(), $entity, array(
            'action' => $this->generateUrl('typeAddon_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter'));

        return $form;
    }

    /**
     * Displays a form to create a new TypeAddon entity.
     *
     */
    public function newAction()
    {
        $entity = new TypeAddon();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJJeuBundle:TypeAddon:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TypeAddon entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:TypeAddon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeAddon entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJJeuBundle:TypeAddon:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TypeAddon entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:TypeAddon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Impossible de trouver l\'entité typeAddon.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('JDJJeuBundle:TypeAddon:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a TypeAddon entity.
    *
    * @param TypeAddon $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TypeAddon $entity)
    {
        $form = $this->createForm(new TypeAddonType(), $entity, array(
            'action' => $this->generateUrl('typeAddon_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Mettre à jour'));

        return $form;
    }

    /**
     * Edits an existing TypeAddon entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:TypeAddon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Impossible de trouver l\'entité typeAddon.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le type d\'addon a bien été enregistré');
            return $this->redirect($this->generateUrl('typeAddon'));
        }

        return $this->render('JDJJeuBundle:TypeAddon:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a TypeAddon entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJJeuBundle:TypeAddon')->find($id);
            $request->getSession()->getFlashBag()->add('success', 'Le type d\'addon a bien été supprimé');
            if (!$entity) {
                throw $this->createNotFoundException('Impossible de trouver l\'entité typeAddon.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('typeAddon'));
    }

    /**
     * Creates a form to delete a TypeAddon entity by id.
     *
     * @param mixed $id The entity id
     *$request->getSession()->getFlashBag()->add('success', 'Le type d\'addon a bien été enregistré');
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typeAddon_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
