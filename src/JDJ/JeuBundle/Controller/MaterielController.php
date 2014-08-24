<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 08/06/2014
 * Time: 14:28
 */

namespace JDJ\JeuBundle\Controller;


use JDJ\JeuBundle\Entity\Materiel;
use JDJ\JeuBundle\Form\MaterielType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MaterielController extends Controller
{
    /**
     * Lists all Materiel entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJJeuBundle:Materiel')->findAll();

        $deleteForms = array();
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('JDJJeuBundle:Materiel:index.html.twig', array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
        ));
    }
    /**
     * Creates a new Materiel entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Materiel();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('materiel'));
        }

        return $this->render('JDJJeuBundle:Materiel:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Materiel entity.
     *
     * @param Materiel $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Materiel $entity)
    {
        $form = $this->createForm(new MaterielType(), $entity, array(
            'action' => $this->generateUrl('materiel_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Materiel entity.
     *
     */
    public function newAction()
    {
        $entity = new Materiel();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJJeuBundle:Materiel:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Materiel entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Materiel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Materiel entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJJeuBundle:Materiel:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Materiel entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Materiel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Materiel entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('JDJJeuBundle:Materiel:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to edit a Materiel entity.
     *
     * @param Materiel $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Materiel $entity)
    {
        $form = $this->createForm(new MaterielType(), $entity, array(
            'action' => $this->generateUrl('materiel_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    /**
     * Edits an existing Materiel entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Materiel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Materiel entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('materiel'));
        }

        return $this->render('JDJJeuBundle:Materiel:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Materiel entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJJeuBundle:Materiel')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Materiel entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('materiel'));
    }

    /**
     * Creates a form to delete a Materiel entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('materiel_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }
} 