<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/06/2014
 * Time: 12:33
 */

namespace JDJ\JeuBundle\Controller;


use JDJ\JeuBundle\Entity\Caracteristique;
use JDJ\JeuBundle\Form\CaracteristiqueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CaracteristiqueController extends Controller
{

    /**
     * Lists all Caracteristique entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJJeuBundle:Caracteristique')->findAll();

        $deleteForms = array();
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('JDJJeuBundle:Caracteristique:index.html.twig', array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
        ));
    }
    /**
     * Creates a new Caracteristique entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Caracteristique();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('caracteristique'));
        }

        return $this->render('JDJJeuBundle:Caracteristique:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Caracteristique entity.
     *
     * @param Caracteristique $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Caracteristique $entity)
    {
        $form = $this->createForm(new CaracteristiqueType(), $entity, array(
            'action' => $this->generateUrl('caracteristique_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Caracteristique entity.
     *
     */
    public function newAction()
    {
        $entity = new Caracteristique();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJJeuBundle:Caracteristique:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Caracteristique entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Caracteristique')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Caracteristique entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJJeuBundle:Caracteristique:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Caracteristique entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Caracteristique')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Caracteristique entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('JDJJeuBundle:Caracteristique:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to edit a Caracteristique entity.
     *
     * @param Caracteristique $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Caracteristique $entity)
    {
        $form = $this->createForm(new CaracteristiqueType(), $entity, array(
            'action' => $this->generateUrl('caracteristique_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    /**
     * Edits an existing Caracteristique entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Caracteristique')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Caracteristique entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('caracteristique'));
        }

        return $this->render('JDJJeuBundle:Caracteristique:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Caracteristique entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJJeuBundle:Caracteristique')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Caracteristique entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('caracteristique'));
    }

    /**
     * Creates a form to delete a Caracteristique entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('caracteristique_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }
}