<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 11/06/2014
 * Time: 23:30
 */

namespace JDJ\JeuBundle\Controller;


use JDJ\JeuBundle\Entity\Jeu;
use JDJ\JeuBundle\Form\JeuType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class JeuController extends Controller
{
    /**
     * Finds and displays a Jeu entity.
     *
     */
    public function showAction($id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Jeu $entity */
        $entity = $em->getRepository('JDJJeuBundle:Jeu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Jeu entity.');
        }

        /**
         * Redirect the slug is incorrect
         */
        if ($slug !== $entity->getSlug()) {
            return $this->redirect($this->generateUrl('jeu_show', array(
                        'id' => $id,
                        'slug' => $entity->getSlug(),
                    )
                )
            );
        }

        return $this->render('JDJJeuBundle:Jeu:show.html.twig', array(
                'jeu' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing Caracteristique entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Jeu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Jeu entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('JDJJeuBundle:Jeu:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    public function indexAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJJeuBundle:Jeu')->findAll();

        return $this->render('JDJJeuBundle:Jeu:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a form to edit a Jeu entity.
     *
     * @param Jeu $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Jeu $entity)
    {
        $form = $this->createForm(new JeuType(), $entity, array(
            'action' => $this->generateUrl('jeu_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Edits an existing Jeu entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Jeu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Jeu entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('jeu_show', array(
                        'id' => $id,
                        'slug' => "test",
                    )
                )
            );
        }

        return $this->render('JDJJeuBundle:Jeu:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }
} 