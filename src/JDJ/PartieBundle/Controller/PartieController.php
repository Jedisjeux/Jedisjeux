<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 27/08/2014
 * Time: 23:35
 */

namespace JDJ\PartieBundle\Controller;


use JDJ\JeuBundle\Entity\Jeu;
use JDJ\PartieBundle\Entity\Joueur;
use JDJ\PartieBundle\Entity\Partie;
use JDJ\PartieBundle\Form\PartieType;
use JDJ\PartieBundle\Repository\PartieRepository;
use JDJ\UserBundle\Entity\User;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PartieController
 * @package JDJ\PartieBundle\Controller
 */
class PartieController extends Controller
{
    /**
     * Lists all Partie entities.
     *
     * @Route("/partie", name="partie")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var PartieRepository $partieReposititory */
        $partieReposititory = $em->getRepository('JDJPartieBundle:Partie');

        /** @var Pagerfanta $entities */
        $entities = $partieReposititory->createPaginator(null, array('createdAt' => 'desc'));
        $entities
            ->setCurrentPage($request->get('page', 1))
            ->setMaxPerPage(16);

        $deleteForms = array();
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('partie/index.html.twig', array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
        ));
    }

    /**
     * Lists all Partie entities.
     *
     * @Route("/partie/user/{user}/{slug}", name="user_partie")
     *
     * @ParamConverter("entity", class="JDJUserBundle:User", name="user")
     *
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userIndexAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var PartieRepository $partieReposititory */
        $partieReposititory = $em->getRepository('JDJPartieBundle:Partie');

        /** @var Pagerfanta $entities */
        $entities = $partieReposititory->findByUser($user);
        $entities
            ->setCurrentPage($request->get('page', 1))
            ->setMaxPerPage(16);

        $deleteForms = array();
        /** @var Partie $entity */
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('partie/index.html.twig', array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
        ));
    }

    /**
     * Displays a form to create a new Partie entity.
     *
     * @Route("/jeu/{idJeu}/partie/new", name="partie_new")
     */
    public function newAction($idJeu)
    {
        $jeu = $this->findJeu($idJeu);

        $entity = new Partie();
        $form = $this->createCreateForm($entity);

        /**
         * Pre populate data
         */
        $form->get('jeu')->setData($jeu);
        $form->get('playedAt')->setData(new \DateTime());

        return $this->render('partie/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Partie entity.
     *
     * @Route("/partie/create", name="partie_create")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = new Partie();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setAuthor($user);

            /*
             * Adding User on party
             */
            $entity->addUser($user);

            /**
             * adding User as a player on party
             */
            $joueur = new Joueur();
            $joueur
                ->setUser($user)
                ->setPartie($entity);
            $entity->addJoueur($joueur);

            $em->persist($entity);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La partie a bien été enregistrée');
            return $this->redirect($this->generateUrl('partie_show', array(
                'id' => $entity->getId(),
                'slug' => $entity->getJeu()->getSlug(),
            )));
        }

        return $this->render('partie/edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Client entity.
     *
     * @param Partie $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Partie $entity)
    {
        $form = $this->createForm(new PartieType(), $entity, array(
            'action' => $this->generateUrl('partie_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Finds and displays a Partie entity.
     *
     * @Route("/partie/{id}/jeu/{slug}", name="partie_show")
     *
     * @ParamConverter("entity", class="JDJPartieBundle:Partie")
     *
     * @param Partie $entity
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Partie $entity, $slug)
    {
        /**
         * Redirect the slug is incorrect
         */
        if ($slug !== $entity->getJeu()->getSlug()) {
            return $this->redirect($this->generateUrl('partie_show', array(
                        'id' => $entity->getId(),
                        'slug' => $entity->getJeu()->getSlug(),
                    )
                )
            );
        }

        $deleteForm = $this->createDeleteForm($entity->getId());

        return $this->render('partie/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Partie entity.
     *
     * @Route("/partie/{id}/edit", name="partie_edit")
     *
     * @ParamConverter("entity", class="JDJPartieBundle:Partie")
     *
     * @param Partie $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Partie $entity)
    {
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity->getId());

        return $this->render('partie/edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Partie entity.
     *
     * @Route("/partie/{id}/update", name="partie_update")
     *
     * @ParamConverter("entity", class="JDJPartieBundle:Partie")
     *
     * @param Request $request
     * @param Partie $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Partie $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($entity->getId());
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);


        if ($editForm->isValid()) {
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Vos modifications ont bien été enregistrées!');
            return $this->redirect($this->generateUrl('partie_show', array(
                'id' => $entity->getId(),
                'slug' => $entity->getJeu()->getSlug(),
            )));
        }

        return $this->render('partie/edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Partie entity.
     *
     * @Route("/partie/{id}/delete", name="partie_delete")
     *
     * @ParamConverter("entity", class="JDJPartieBundle:Partie")
     */
    public function deleteAction(Request $request, Partie $entity)
    {
        $form = $this->createDeleteForm($entity->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $request->getSession()->getFlashBag()->add('success', 'La partie a été supprimée');

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('partie'));
    }

    /**
     * Creates a form to delete a Client entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('partie_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm();
    }

    /**
     * Find Jeu Entity
     *
     * @param $idJeu
     * @return Jeu
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function findJeu($idJeu)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Jeu $jeu */
        $jeu = $em->getRepository('JDJJeuBundle:Jeu')->find($idJeu);

        if (!$jeu) {
            throw $this->createNotFoundException('Unable to find Jeu entity.');
        }

        return $jeu;
    }

    /**
     * Creates a form to edit a Partie entity.
     *
     * @param Partie $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Partie $entity)
    {
        $form = $this->createForm(new PartieType(), $entity, array(
            'action' => $this->generateUrl('partie_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }
} 