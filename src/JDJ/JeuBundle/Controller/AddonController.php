<?php

namespace JDJ\JeuBundle\Controller;

use Symfony\Component\Debug\Debug;
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
    public function indexAction($jeu_id)
    {
        $em = $this->getDoctrine()->getManager();

        //On récupère le jeu
        $jeu = $em->getRepository('JDJJeuBundle:Jeu')->find($jeu_id);

        //On récupère ensuite les addons liés au jeu
        $repository = $this->getDoctrine()
            ->getRepository('JDJJeuBundle:Addon');

        $query = $repository->createQueryBuilder('a')
            ->where('a.jeu = :jeu')
            ->setParameter('jeu', $jeu)
            ->orderBy('a.libelle', 'ASC')
            ->getQuery();

        $addons = $query->getResult();


        //Préparation du formulaire de delete
        $deleteForms = array();
        foreach ($addons as $addon) {
            $deleteForms[$addon->getId()] = $this->createDeleteForm($jeu_id, $addon->getId())->createView();
        }



        return $this->render('JDJJeuBundle:Addon:index.html.twig', array(
            'entities' => $addons,
            'jeu' => $jeu,
            'deleteForms' => $deleteForms,
        ));
    }

    /**
     * Creates a new Addon entity.
     *
     */
    public function createAction(Request $request, $jeu_id)
    {
        $entity = new Addon();

        //On récupère le jeu
        $jeu = $this->getDoctrine()->getManager()->getRepository('JDJJeuBundle:Jeu')->find($jeu_id);

        $form = $this->createCreateForm($entity, $jeu);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'L\'Addon a bien été enregistré');
            return $this->redirect($this->generateUrl('jeu_addon', array('jeu_id' => $jeu_id)));
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
     * @param $jeu
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Addon $entity, $jeu)
    {
        //On set le jeu de l'Addon
        $entity->setJeu($jeu);

        $form = $this->createForm(new AddonType(), $entity, array(
            'action' => $this->generateUrl('jeu_addon_create', array("jeu_id"=>$jeu->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter un Addon'));

        return $form;
    }

    /**
     * Displays a form to create a new Addon entity.
     *
     */
    public function newAction($jeu_id)
    {
        //On récupère le jeu
        $jeu = $this->getDoctrine()->getManager()->getRepository('JDJJeuBundle:Jeu')->find($jeu_id);

        $entity = new Addon();
        $form   = $this->createCreateForm($entity, $jeu);

        return $this->render('JDJJeuBundle:Addon:new.html.twig', array(
            'entity' => $entity,
            'jeu' => $jeu,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Addon entity.
     *
     */
    public function showAction($jeu_id, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Addon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Addon entity.');
        }

        $deleteForm = $this->createDeleteForm($jeu_id, $id);

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

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('JDJJeuBundle:Addon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Addon entity.');
        }

        //On récupère le jeu
        $jeu = $this->getDoctrine()->getManager()->getRepository('JDJJeuBundle:Jeu')->find($jeu_id);
        $editForm = $this->createEditForm($entity);

        return $this->render('JDJJeuBundle:Addon:new.html.twig', array(
            'entity'      => $entity,
            'jeu' => $jeu,
            'form'   => $editForm->createView(),
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
            'action' => $this->generateUrl('jeu_addon_update', array('jeu_id' => $entity->getJeu()->getId(), 'id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Mettre à jour'));

        return $form;
    }

    /**
     * Edits an existing Addon entity.
     *
     */
    public function updateAction(Request $request, $jeu_id, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Addon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Addon entity.');
        }

        $deleteForm = $this->createDeleteForm($jeu_id, $id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'L\'Addon a bien été enregistré');
            return $this->redirect($this->generateUrl('jeu_addon', array('jeu_id' => $jeu_id)));
        }

        return $this->render('JDJJeuBundle:Addon:new.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Addon entity.
     *
     */
    public function deleteAction(Request $request, $jeu_id, $id)
    {
        $form = $this->createDeleteForm($jeu_id, $id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJJeuBundle:Addon')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Addon entity.');
            }

            $request->getSession()->getFlashBag()->add('success', 'L\'Addon a bien été supprimé');
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('jeu_addon', array('jeu_id' => $jeu_id)));
    }

    /**
     * Creates a form to delete a Addon entity by id.
     *
     * @param $jeu_id
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($jeu_id, $id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('jeu_addon_delete', array(
                                                                    'jeu_id' => $jeu_id,
                                                                    'id' => $id,
                                                                    )))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
