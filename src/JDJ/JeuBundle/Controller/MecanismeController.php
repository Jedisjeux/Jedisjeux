<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 07/06/2014
 * Time: 21:29
 */

namespace JDJ\JeuBundle\Controller;

use JDJ\JeuBundle\Entity\JeuRepository;
use JDJ\JeuBundle\Entity\Mecanisme;
use JDJ\JeuBundle\Form\MecanismeType;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MecanismeController extends Controller
{

    /**
     * Lists all Mecanisme entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJJeuBundle:Mecanisme')->findAll();

        $deleteForms = array();
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('JDJJeuBundle:Mecanisme:index.html.twig', array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
        ));
    }
    /**
     * Creates a new Mecanisme entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Mecanisme();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mecanisme'));
        }

        return $this->render('JDJJeuBundle:Mecanisme:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Mecanisme entity.
     *
     * @param Mecanisme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Mecanisme $entity)
    {
        $form = $this->createForm(new MecanismeType(), $entity, array(
            'action' => $this->generateUrl('mecanisme_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Mecanisme entity.
     *
     */
    public function newAction()
    {
        $entity = new Mecanisme();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJJeuBundle:Mecanisme:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Mecanisme entity.
     *
     */
    public function showAction(Request $request, $id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Mecanisme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mecanisme entity.');
        }

        /**
         * Redirect the slug is incorrect
         */
        if ($slug !== $entity->getSlug()) {
            return $this->redirect($this->generateUrl('mecanisme_show', array(
                'id' => $id,
                'slug' => $entity->getSlug(),
                    )
                )
            );
        }

        /** @var JeuRepository $jeuReposititory */
        $jeuReposititory = $em->getRepository('JDJJeuBundle:Jeu');
        /** @var Pagerfanta $jeux */
        $jeux = $jeuReposititory->createPaginator(array("mecanisme" => $entity));
        $jeux->setMaxPerPage(16);
        $jeux->setCurrentPage($request->get('page', 1));


        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJJeuBundle:Mecanisme:show.html.twig', array(
            'entity'      => $entity,
            'jeux'        => $jeux,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Mecanisme entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Mecanisme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mecanisme entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('JDJJeuBundle:Mecanisme:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to edit a Mecanisme entity.
     *
     * @param Mecanisme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Mecanisme $entity)
    {
        $form = $this->createForm(new MecanismeType(), $entity, array(
            'action' => $this->generateUrl('mecanisme_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    /**
     * Edits an existing Mecanisme entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Mecanisme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mecanisme entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('mecanisme'));
        }

        return $this->render('JDJJeuBundle:Mecanisme:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Mecanisme entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJJeuBundle:Mecanisme')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Mecanisme entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mecanisme'));
    }

    /**
     * Creates a form to delete a Mecanisme entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('mecanisme_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }
} 