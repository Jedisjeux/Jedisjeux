<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 14/09/2014
 * Time: 11:18
 */

namespace JDJ\CoreBundle\Controller;


use JDJ\CoreBundle\Entity\Cible;
use JDJ\CoreBundle\Entity\CibleRepository;
use JDJ\CoreBundle\Form\CibleType;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CibleController extends Controller
{
    /**
     * Lists all Cible entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJCoreBundle:Cible')->findAll();

        $deleteForms = array();
        /** @var Cible $entity */
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('JDJCoreBundle:Cible:index.html.twig', array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
        ));
    }

    /**
     * Finds and displays a Cible entity.
     *
     */
    public function showAction(Request $request, $id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Cible $entity */
        $entity = $em->getRepository('JDJCoreBundle:Cible')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cible entity.');
        }

        /**
         * Redirect the slug is incorrect
         */
        if ($slug !== $entity->getSlug()) {
            return $this->redirect($this->generateUrl('cible_show', array(
                        'id' => $id,
                        'slug' => $entity->getSlug(),
                    )
                )
            );
        }
        /** @var CibleRepository $cibleReposititory */
        $cibleReposititory = $em->getRepository('JDJJeuBundle:Jeu');
        /** @var Pagerfanta $jeux */
        $jeux = $cibleReposititory->createPaginator(array("cible" => $entity));
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
     * Displays a form to edit an existing Cible entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJCoreBundle:Cible')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cible entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('JDJCoreBundle:Cible:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Edits an existing Cible entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJCoreBundle:Cible')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cible entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cible'));
        }

        return $this->render('JDJCoreBundle:Cible:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Mecanisme entity.
     *
     * @param Cible $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Cible $entity)
    {
        $form = $this->createForm(new CibleType(), $entity, array(
            'action' => $this->generateUrl('cible_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }


    /**
     * Creates a form to delete a Cible entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cible_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }

} 