<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 07/06/2014
 * Time: 21:29
 */

namespace JDJ\JeuBundle\Controller;

use JDJ\JeuBundle\Entity\JeuRepository;
use JDJ\JeuBundle\Entity\Mechanism;
use JDJ\JeuBundle\Form\MechanismType;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MechanismController extends Controller
{

    /**
     * Lists all Mechanism entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJJeuBundle:Mechanism')->findAll();

        $deleteForms = array();
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('jeu/mechanism/index.html.twig', array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
        ));
    }
    /**
     * Creates a new Mechanism entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Mechanism();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mechanism'));
        }

        return $this->render('jeu/mechanism/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Mechanism entity.
     *
     * @param Mechanism $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Mechanism $entity)
    {
        $form = $this->createForm(new MechanismType(), $entity, array(
            'action' => $this->generateUrl('mechanism_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Mechanism entity.
     *
     */
    public function newAction()
    {
        $entity = new Mechanism();
        $form   = $this->createCreateForm($entity);

        return $this->render('jeu/mechanism/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Mechanism entity.
     *
     */
    public function showAction(Request $request, $id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Mechanism')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mechanism entity.');
        }

        /**
         * Redirect the slug is incorrect
         */
        if ($slug !== $entity->getSlug()) {
            return $this->redirect($this->generateUrl('mechanism_show', array(
                'id' => $id,
                'slug' => $entity->getSlug(),
                    )
                )
            );
        }

        /** @var JeuRepository $jeuReposititory */
        $jeuReposititory = $em->getRepository('JDJJeuBundle:Jeu');
        /** @var Pagerfanta $jeux */
        $jeux = $jeuReposititory->createPaginator(array("mechanism" => $entity));
        $jeux->setMaxPerPage(16);
        $jeux->setCurrentPage($request->get('page', 1));


        $deleteForm = $this->createDeleteForm($id);

        return $this->render('jeu/mechanism/show.html.twig', array(
            'entity'      => $entity,
            'jeux'        => $jeux,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Mechanism entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Mechanism')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mechanism entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('jeu/mechanism/edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to edit a Mechanism entity.
     *
     * @param Mechanism $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Mechanism $entity)
    {
        $form = $this->createForm(new MechanismType(), $entity, array(
            'action' => $this->generateUrl('mechanism_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    /**
     * Edits an existing Mechanism entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Mechanism')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mechanism entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('mechanism'));
        }

        return $this->render('jeu/mechanism/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Mechanism entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJJeuBundle:Mechanism')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Mechanism entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mechanism'));
    }

    /**
     * Creates a form to delete a Mechanism entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('mechanism_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }
} 