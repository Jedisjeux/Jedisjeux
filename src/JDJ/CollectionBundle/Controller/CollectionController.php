<?php

namespace JDJ\CollectionBundle\Controller;

use Doctrine\Common\Util\Debug;
use JDJ\CollectionBundle\Service\CollectionService;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\CollectionBundle\Entity\Collection;
use JDJ\CollectionBundle\Form\CollectionType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CollectionController
 *
 * @package JDJ\CollectionBundle\Controller
 * @Route("/collection")
 */
class CollectionController extends Controller
{


    /**
     * Lists all Collection entities.
     *
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $collections = $em->getRepository('JDJCollectionBundle:Collection')->findAll();

        return $this->render('JDJCollectionBundle:Collection:index.html.twig', array(
            'entities' => $collections,
        ));
    }

    /**
     * Creates a new Collection entity.
     *
     * @Route("/{jeu}/{user}/create")
     * @ParamConverter("jeu", class="JDJJeuBundle:Jeu")
     * @ParamConverter("user", class="JDJUserBundle:User")
     * @Method({"POST"})
     */
    public function createAction(Jeu $jeu,User $user)
    {

        if($jeu && $user && ($_POST['name'] !== "")) {
            //create the collection
            $collection = $this
                ->getCollectionService()
                ->createCollection($user, $_POST['name'], $_POST['description']);

            //add the game to the collection
            $this
                ->getCollectionService()
                ->addGameCollection($jeu, $collection);

            //save the collection
            $this
                ->getCollectionService()
                ->saveCollection($collection);


            return new JsonResponse(array(
                "status" => Response::HTTP_CREATED,
            ));
        } else {
            return new JsonResponse(array(
                "status" => Response::HTTP_BAD_REQUEST,
            ));
        }

    }


    /**
     * Updates a collection to add a game to it
     *
     * @Route("/{jeu}/{collection}/add-game")
     * @ParamConverter("jeu", class="JDJJeuBundle:Jeu")
     * @ParamConverter("collection", class="JDJCollectionBundle:Collection")
     * @Method({"GET"})
     */
    public function addGameAction(Jeu $jeu, Collection $collection)
    {

        //add the game to the collection
        if($jeu && $collection) {
            $collection = $this
                ->getCollectionService()
                ->addGameCollection($jeu, $collection);
        } else {
            return new JsonResponse(array(
                "status" => Response::HTTP_BAD_REQUEST,
            ));
        }

        //Save the collection
        $this
            ->getCollectionService()
            ->saveCollection($collection);

        return new JsonResponse(array(
            "status" => Response::HTTP_OK,
        ));

    }


    /**
     * Displays a form to edit an existing Collection entity.
     *
     * @Route("/{id}/edit")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJCollectionBundle:Collection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Collection entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJCollectionBundle:Collection:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Collection entity.
     *
     * @param Collection $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Collection $entity)
    {
        $form = $this->createForm(new CollectionType(), $entity, array(
            'action' => $this->generateUrl('collection_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Collection entity.
     *
     * @Route("/{id}/update")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJCollectionBundle:Collection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Collection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('collection_edit', array('id' => $id)));
        }

        return $this->render('JDJCollectionBundle:Collection:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }



    /**
     * Finds and displays a Collection entity.
     *
     * @Route("/{id}/show")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('JDJCollectionBundle:Collection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Collection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJCollectionBundle:Collection:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }




    /**
     * Deletes a Collection entity.
     *
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJCollectionBundle:Collection')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Collection entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('collection'));
    }

    /**
     * Creates a form to delete a Collection entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('collection_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * @return CollectionService
     */
    private function getCollectionService()
    {
        return $this->container->get('app.service.collection');
    }
}
