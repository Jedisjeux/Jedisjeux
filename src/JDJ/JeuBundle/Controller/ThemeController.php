<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 07/06/2014
 * Time: 22:31
 */

namespace JDJ\JeuBundle\Controller;


use JDJ\JeuBundle\Entity\JeuRepository;
use JDJ\JeuBundle\Entity\Theme;
use JDJ\JeuBundle\Form\ThemeType;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ThemeController extends Controller
{

    /**
     * Lists all Theme entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJJeuBundle:Theme')->findAll();

        $deleteForms = array();
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('JDJJeuBundle:Theme:index.html.twig', array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
        ));
    }
    /**
     * Creates a new Theme entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Theme();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('theme'));
        }

        return $this->render('JDJJeuBundle:Theme:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Theme entity.
     *
     * @param Theme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Theme $entity)
    {
        $form = $this->createForm(new ThemeType(), $entity, array(
            'action' => $this->generateUrl('theme_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Theme entity.
     *
     */
    public function newAction()
    {
        $entity = new Theme();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJJeuBundle:Theme:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Theme entity.
     *
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Theme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Theme entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        /** @var JeuRepository $jeuReposititory */
        $jeuReposititory = $em->getRepository('JDJJeuBundle:Jeu');
        /** @var Pagerfanta $jeux */
        $jeux = $jeuReposititory->createPaginator(array("theme" => $entity));
        $jeux->setMaxPerPage(16);
        $jeux->setCurrentPage($request->get('page', 1));

        return $this->render('JDJJeuBundle:Theme:show.html.twig', array(
            'entity'      => $entity,
            'jeux'        => $jeux,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Theme entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Theme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Theme entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('JDJJeuBundle:Theme:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to edit a Theme entity.
     *
     * @param Theme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Theme $entity)
    {
        $form = $this->createForm(new ThemeType(), $entity, array(
            'action' => $this->generateUrl('theme_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    /**
     * Edits an existing Theme entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Theme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Theme entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('theme'));
        }

        return $this->render('JDJJeuBundle:Theme:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Theme entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJJeuBundle:Theme')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Theme entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('theme'));
    }

    /**
     * Creates a form to delete a Theme entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('theme_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }
} 