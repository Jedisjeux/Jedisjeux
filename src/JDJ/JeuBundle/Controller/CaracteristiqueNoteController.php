<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/06/2014
 * Time: 17:51
 */

namespace JDJ\JeuBundle\Controller;


use JDJ\JeuBundle\Entity\CaracteristiqueNote;
use JDJ\JeuBundle\Form\CaracteristiqueNoteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CaracteristiqueNoteController extends Controller {

    /**
     * Displays a form to edit an existing CaracteristiqueNote entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:CaracteristiqueNote')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CaracteristiqueNote entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('JDJJeuBundle:CaracteristiqueNote:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to edit a CaracteristiqueNote entity.
     *
     * @param CaracteristiqueNote $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CaracteristiqueNote $entity)
    {
        $form = $this->createForm(new CaracteristiqueNoteType(), $entity, array(
            'action' => $this->generateUrl('caracteristiqueNote_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Edits an existing CaracteristiqueNote entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:CaracteristiqueNote')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CaracteristiqueNote entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('caracteristique'));
        }

        return $this->render('JDJJeuBundle:CaracteristiqueNote:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
} 