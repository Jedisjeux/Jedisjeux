<?php

namespace JDJ\CollectionBundle\Controller;

use Doctrine\Common\Util\Debug;
use JDJ\CollectionBundle\Entity\UserGameAttribute;
use JDJ\CollectionBundle\Service\CollectionService;
use JDJ\CollectionBundle\Service\UserGameAttributeService;
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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

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
     * @Route("/", name="collection")
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
     * Displays the collection modal to create or update list with a game
     *
     * @Route("/{jeu}/collection-modal", name="collection_modal")
     * @ParamConverter("jeu", class="JDJJeuBundle:Jeu")
     */
    public function modalDisplayAction(Jeu $jeu)
    {

        /**
         * Checks if the user is connected
         */
        $collectionList = null;
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();

            /**
             * Get the Collection list from the connected user
             */
            $collectionList = $this
                ->getCollectionService()
                ->getUserCollection($user);
        }

        return $this->render('jeu/show/modal/collection.html.twig', array(
            'collectionList' => $collectionList,
            'jeu' => $jeu,
        ));
    }

    /**
     * Creates a new Collection entity.
     *
     * @Route("/{jeu}/{user}/create", name="create_collection", options={"expose"=true})
     * @ParamConverter("jeu", class="JDJJeuBundle:Jeu")
     * @ParamConverter("user", class="JDJUserBundle:User")
     * @Method({"POST"})
     */
    public function createAction(Jeu $jeu, User $user)
    {

        if ($jeu && $user && ($_POST['name'] !== "")) {
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
            $response = new JsonResponse(array(
                "status" => Response::HTTP_BAD_REQUEST,
            ));

            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
        }

    }


    /**
     * Updates a collection to add a game to it
     *
     * @Route("/{jeu}/{collection}/add-game", name="add_game_collection", options={"expose"=true})
     * @ParamConverter("jeu", class="JDJJeuBundle:Jeu")
     * @ParamConverter("collection", class="JDJCollectionBundle:Collection")
     * @Method({"GET"})
     */
    public function addGameAction(Jeu $jeu, Collection $collection)
    {

        //add the game to the collection
        if ($jeu && $collection) {
            $collection = $this
                ->getCollectionService()
                ->addGameCollection($jeu, $collection);
        } else {
            $response = new JsonResponse(array(
                "status" => Response::HTTP_BAD_REQUEST,
            ));

            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
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
     * returns the user lists
     *
     * @Route("/{user}/user-list", name="user_list", options={"expose"=true})
     * @ParamConverter("user", class="JDJUserBundle:User")
     * @Method({"GET"})
     */
    public function userListAction(User $user)
    {

        //add the game to the collection
        if ($user) {
            $tabCollection = $this
                ->getCollectionService()
                ->getUserCollection($user);

            $jsonCollection = $this
                ->getCollectionService()
                ->prepareCollectionJsonData($tabCollection);
        } else {
            $response = new JsonResponse(array(
                "status" => Response::HTTP_BAD_REQUEST,
            ));

            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
        }

        return new JsonResponse(array(
            "status" => Response::HTTP_OK,
            "tabCollection" => $jsonCollection,
        ));

    }


    /**
     * returns the user lists
     *
     * @Route("/mes-listes", name="my_collections", options={"expose"=true})
     */
    public function userListPageAction(Request $request)
    {

        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $user = $this->get('security.context')->getToken()->getUser();
            $tabCollection = $this
                ->getCollectionService()
                ->getUserCollection($user);

            /** gets the user usergameattribute */
            $tabFavorite = $this
                ->getUserGameAttributeService()
                ->getFavorites($user);
            $tabOwned = $this
                ->getUserGameAttributeService()
                ->getOwned($user);
            $tabPlayed = $this
                ->getUserGameAttributeService()
                ->getPlayed($user);
            $tabWanted = $this
                ->getUserGameAttributeService()
                ->getWanted($user);

        } else {
            $request->getSession()->getFlashBag()->add('error', 'Vous devez être connecté.');
            return $this->redirect($this->generateUrl('jdj_web_homepage'));
        }

        return $this->render('collection/index.html.twig', array(
            'entities' => $tabCollection,
            'tabFavorite' => $tabFavorite,
            'tabOwned' => $tabOwned,
            'tabPlayed' => $tabPlayed,
            'tabWanted' => $tabWanted,
        ));

    }


    /**
     * Displays a form to edit an existing Collection entity.
     *
     * @Route("/{id}/edit", name="collection_edit")
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
     * Finds and displays a Collection entity for administration.
     *
     * @Route("/{id}/show", name="collection_show")
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
     * Finds and displays a Collection page
     *
     * @Route("/{id}/list", name="my-list")
     */
    public function collectionPageAction($id, Request $request)
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $collection = $em->getRepository('JDJCollectionBundle:Collection')->find($id);

            if (!$collection) {
                throw $this->createNotFoundException('La liste n\'existe pas.');
                return $this->redirect($this->generateUrl('jdj_web_homepage'));
            }
        } else {
            $request->getSession()->getFlashBag()->add('error', 'Vous devez être connecté.');
            return $this->redirect($this->generateUrl('jdj_web_homepage'));
        }


        /** @var JeuRepository $jeuReposititory */
        $listeElementRepository = $em->getRepository('JDJCollectionBundle:ListElement');
        /** @var Pagerfanta $jeux */
        $listElements = $listeElementRepository->createPaginator(array('collection' => $collection));
        $listElements->setMaxPerPage(10);
        $listElements->setCurrentPage($request->get('page', 1));

        return $this->render('collection/show.html.twig', array(
            'collection' => $collection,
            'listElements' => $listElements,
        ));
    }

    /**
     * Finds and displays a Collection page
     *
     * @Route("/{type}/game-list", name="my-list-usergameattribute")
     */
    public function userGameAttributeCollectionPageAction($type, Request $request)
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $user = $this->get('security.context')->getToken()->getUser();

            switch ($type)
            {
                case UserGameAttribute::FAVORITE:
                    $userGameAttributes = $this
                        ->getUserGameAttributeService()
                        ->getFavorites($user);
                    $title = "Mes coups de coeur";
                    break;
                case UserGameAttribute::OWNED:
                    $userGameAttributes = $this
                        ->getUserGameAttributeService()
                        ->getOwned($user);
                    $title = "Ma ludothèque";
                    break;
                case UserGameAttribute::PLAYED:
                    $userGameAttributes = $this
                        ->getUserGameAttributeService()
                        ->getPlayed($user);
                    $title = "J'y ai joué";
                    break;
                case UserGameAttribute::WANTED:
                    $userGameAttributes = $this
                        ->getUserGameAttributeService()
                        ->getWanted($user);
                    $title = "Mes souhaits";
                    break;
            }

            if (!$userGameAttributes) {
                throw $this->createNotFoundException('La liste n\'existe pas.');
                return $this->redirect($this->generateUrl('jdj_web_homepage'));
            }
        } else {
            $request->getSession()->getFlashBag()->add('error', 'Vous devez être connecté.');
            return $this->redirect($this->generateUrl('jdj_web_homepage'));
        }


        /** @var UserGameAttributeRepository $UserGameAttributeReposititory */
        $em = $this->getDoctrine()->getManager();
        $userGameAttributeRepository = $em->getRepository('JDJCollectionBundle:UserGameAttribute');
        /** @var Pagerfanta $jeux */
        $userGameAttributes = $userGameAttributeRepository->createPaginator(array('userGameAttribute' => $userGameAttributes));
        $userGameAttributes->setMaxPerPage(10);
        $userGameAttributes->setCurrentPage($request->get('page', 1));

        return $this->render('collection/show-usergameattribute.html.twig', array(
            'title' => $title,
            'userGameAttributes' => $userGameAttributes,
        ));
    }



    /**
     * Deletes a Collection entity.
     *
     * @Route("/{id}/delete", name="collection_delete")
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

    /**
     * @return UserGameAttributeService
     */
    private function getUserGameAttributeService()
    {
        return $this->container->get('app.service.user.game.attribute');
    }
}
