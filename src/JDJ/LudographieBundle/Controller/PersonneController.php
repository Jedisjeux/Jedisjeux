<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 25/06/2014
 * Time: 20:04
 */

namespace JDJ\LudographieBundle\Controller;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use JDJ\LudographieBundle\Entity\Personne;
use JDJ\LudographieBundle\Form\PersonneType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JDJ\WebBundle\Entity\EntityRepository;
use JDJ\JeuBundle\Entity\JeuRepository;

class PersonneController extends Controller
{

    /**
     * Find and display all the Personne entities
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $itemCountPerPage = 16;

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('JDJLudographieBundle:Personne')->createQueryBuilder('o');
        $paginator = $this->getPaginator($queryBuilder);
        $paginator->setMaxPerPage($itemCountPerPage);
        $paginator->setCurrentPage($request->get('page', 1));

        return $this->render('JDJLudographieBundle:Personne:index.html.twig', array(
            'entities' => $paginator,
        ));
    }

    /**
     * Finds and displays a Jeu entity.
     *
     */
    public function showAction(Request $request, $id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Personne $entity */
        $entity = $em->getRepository('JDJLudographieBundle:Personne')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Personne entity.');
        }

        /**
         * Redirect the slug is incorrect
         */
        if ($slug !== $entity->getSlug()) {
            return $this->redirect($this->generateUrl('personne_show', array(
                        'id' => $id,
                        'slug' => $entity->getSlug(),
                    )
                )
            );
        }

        /** @var JeuRepository $jeuReposititory */
        $jeuReposititory = $em->getRepository('JDJJeuBundle:Jeu');
        /** @var PagerFanta $jeux */
        $jeux = $jeuReposititory->createPaginator(array("personne" => $entity));
        $jeux->setMaxPerPage(16);
        $jeux->setCurrentPage($request->get('page', 1));


        /** @var EntityRepository $userReviewReposititory */
        $userReviewReposititory = $em->getRepository('JDJUserReviewBundle:UserReview');
        /** @var PagerFanta $userReviews */
        $userReviews = $userReviewReposititory->createPaginator(array("personne" => $entity));
        $userReviews->setMaxPerPage(10);
        $userReviews->setCurrentPage($request->get('page', 1));


        return $this->render('JDJLudographieBundle:Personne:show.html.twig', array(
                'personne' => $entity,
                'jeux' => $jeux,
                'userReviews' => $userReviews,
            )
        );
    }

    /**
     * Displays a form to edit an existing Personne entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJLudographieBundle:Personne')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Personne entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('JDJLudographieBundle:Personne:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Edits an existing Jeu entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Personne $entity */
        $entity = $em->getRepository('JDJLudographieBundle:Personne')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Personne entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('personne_show', array(
                        'id' => $id,
                        'slug' => $entity->getSlug(),
                    )
                )
            );
        }

        return $this->render('JDJLudographieBundle:Personne:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Jeu entity.
     *
     * @param Personne $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Personne $entity)
    {
        $form = $this->createForm(new PersonneType(), $entity, array(
            'action' => $this->generateUrl('personne_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return Pagerfanta
     */
    public function getPaginator(QueryBuilder $queryBuilder)
    {
        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
    }
} 