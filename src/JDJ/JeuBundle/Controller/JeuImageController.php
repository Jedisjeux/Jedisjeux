<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 16/03/15
 * Time: 23:03
 */

namespace JDJ\JeuBundle\Controller;

use JDJ\JeuBundle\Entity\Jeu;
use JDJ\JeuBundle\Entity\JeuImage;
use JDJ\JeuBundle\Form\JeuImageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JeuImageController extends Controller
{

    /**
     * Displays a form to create a new JeuImage entity.
     * TODO use ParamConverters
     */
    public function newAction($id)
    {
        /** @var Jeu $jeu */
        $jeu = $this->getDoctrine()->getRepository('JDJJeuBundle:Jeu')->find($id);

        if (null === $jeu) {
            throw $this->createNotFoundException('entity jeu not found');
        }

        $entity = new JeuImage();
        $entity->setJeu($jeu);
        $form   = $this->createCreateForm($entity);

        return $this->render('jeu/image/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new JeuImage entity.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new JeuImage();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $imageProperties = $form->get('imageProperties')->getData();

            if (in_array('image_couverture', $imageProperties)) {
                $entity->getJeu()->setImageCouverture($entity->getImage());
            }

            if (in_array('material_image', $imageProperties)) {
                $entity->getJeu()->setMaterialImage($entity->getImage());
            }

            $entity->upload();

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('jeu_show', array(
                'id' => $entity->getJeu()->getId(),
                'slug' => $entity->getJeu()->getSlug(),
            )));
        }

        return $this->render('jeu/image/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Mechanism entity.
     *
     * @param JeuImage $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JeuImage $entity)
    {
        $form = $this->createForm(new JeuImageType(), $entity, array(
            'action' => $this->generateUrl('jeu_image_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to edit an existing JeuImage entity.
     * TODO use ParamConverters
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var JeuImage $entity */
        $entity = $em->getRepository('JDJJeuBundle:JeuImage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JeuImage entity.');
        }

        $form = $this->createEditForm($entity);
        $imageProperties = array();

        if ($entity->getJeu()->getImageCouverture() === $entity->getImage()) {
            $imageProperties[] = "image_couverture";
        }

        if ($entity->getJeu()->getMaterialImage() === $entity->getImage()) {
            $imageProperties[] = "material_image";
        }

        $form->get('imageProperties')->setData($imageProperties);

        return $this->render('jeu/image/edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Edits an existing JeuImage entity.
     *
     * @param int $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var JeuImage $entity */
        $entity = $em->getRepository('JDJJeuBundle:JeuImage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mechanism entity.');
        }

        $oldImage = clone $entity->getImage();

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $imageProperties = $editForm->get('imageProperties')->getData();

            if (null === $editForm->get('image')) {
                $entity->setImage($oldImage);
            }

            $entity->upload();

            if (in_array('image_couverture', $imageProperties)) {
                $entity->getJeu()->setImageCouverture($entity->getImage());
            }

            if (in_array('material_image', $imageProperties)) {
                $entity->getJeu()->setMaterialImage($entity->getImage());
            }

            $em->flush();

            return $this->redirect($this->generateUrl('jeu_show', array(
                'id' => $entity->getJeu()->getId(),
                'slug' => $entity->getJeu()->getSlug(),
            )));
        }

        return $this->render('jeu/image/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a JeuImage entity.
     *
     * @param JeuImage $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JeuImage $entity)
    {
        $form = $this->createForm(new JeuImageType(), $entity, array(
            'action' => $this->generateUrl('jeu_image_update', array(
                'id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
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
            ->setAction($this->generateUrl('jeu_image_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }
} 