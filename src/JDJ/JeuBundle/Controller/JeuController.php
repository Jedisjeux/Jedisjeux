<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 11/06/2014
 * Time: 23:30
 */

namespace JDJ\JeuBundle\Controller;


use Doctrine\ORM\QueryBuilder;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\JeuBundle\Form\JeuType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use JDJ\UserReviewBundle\Entity\UserReviewRepository;

class JeuController extends Controller
{
    /**
     * Finds and displays a Jeu entity.
     *
     */
    public function showAction(Request $request, $id, $slug)
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

        /**
         * Find All User Review entities from this game
         */
        /** @var UserReviewRepository $userReviewReposititory */
        $userReviewReposititory = $em->getRepository('JDJUserReviewBundle:UserReview');
        /** @var PagerFanta $userReviews */
        $userReviews = $userReviewReposititory->createPaginator(array("jeu" => $entity));
        $userReviews->setMaxPerPage(10);
        $userReviews->setCurrentPage($request->get('page', 1));

        return $this->render('JDJJeuBundle:Jeu:show.html.twig', array(
                'jeu' => $entity,
                'userReviews' => $userReviews,
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
        $itemCountPerPage = 16;

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('JDJJeuBundle:Jeu')->createQueryBuilder('o');

        $paginator = $this->getPaginator($queryBuilder);
        $paginator->setMaxPerPage($itemCountPerPage);
        $paginator->setCurrentPage($request->get('page', 1));

        return $this->render('JDJJeuBundle:Jeu:index.html.twig', array(
            'entities' => $paginator,
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