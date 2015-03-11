<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 11/06/2014
 * Time: 23:30
 */

namespace JDJ\JeuBundle\Controller;


use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\JeuBundle\Repository\JeuRepository;
use JDJ\JeuBundle\Entity\Mechanism;
use JDJ\JeuBundle\Entity\Theme;
use JDJ\JeuBundle\Form\GameSearchType;
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
     * Finds and displays a Game entity.
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
         * Find All Game entities from this game
         */
        /** @var UserReviewRepository $userReviewReposititory */
        $userReviewReposititory = $em->getRepository('JDJUserReviewBundle:UserReview');
        /** @var PagerFanta $userReviews */
        $userReviews = $userReviewReposititory->createPaginator(array("jeu" => $entity));
        $userReviews->setMaxPerPage(10);
        $userReviews->setCurrentPage($request->get('page', 1));


        /**
         * Usergameattribute to load if user is connected
         */
        $userGameAttribute = null;
        if($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $user= $this->get('security.context')->getToken()->getUser();

            $em = $em->getRepository("JDJCollectionBundle:UserGameAttribute");
            $userGameAttribute = $em->findOneUserGameAttribute($entity, $user);
        }


        return $this->render('jeu/show.html.twig', array(
                'jeu' => $entity,
                'userReviews' => $userReviews,
                'userGameAttribute' => $userGameAttribute,
            )
        );
    }

    /**
     * Displays a form to edit an existing Game entity.
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

        return $this->render('jeu/edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $itemCountPerPage = 16;

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createSearchingForm($request);

        $criteria = array();
        if ($request->isMethod('GET')) {
            $form->submit($request);

            $criteria = $form->getData();
        }

        /** @var JeuRepository $jeuReopository */
        $jeuReopository = $em->getRepository('JDJJeuBundle:Jeu');

        $paginator = $jeuReopository->advancedSearch($criteria);
        $paginator->setMaxPerPage($itemCountPerPage);
        $paginator->setCurrentPage($request->get('page', 1));


        return $this->render('jeu/index.html.twig', array(
            'jeux' => $paginator,
            'form' => $form->createView(),
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
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function IfYouLikeThisGameAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Jeu $jeu */
        $jeu = $em->getRepository('JDJJeuBundle:Jeu')->find($id);

        if (!$jeu) {
            throw $this->createNotFoundException('Unable to find Jeu entity.');
        }

        /** @var JeuRepository $jeuReposititory */
        $jeuReposititory = $em->getRepository('JDJJeuBundle:Jeu');
        /** @var Pagerfanta $jeux */
        $jeux = $jeuReposititory->getIfYouLikeThisGame($jeu);

        $jeux->setMaxPerPage(8);
        $jeux->setCurrentPage($request->get('page', 1));

        return $this->render('JDJJeuBundle:Jeu:ifYouLikeThisGame.html.twig', array(
            'jeux' => $jeux,
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

    /**
     * Creates a form to search a Game list with criteria.
     *
     * @param Jeu $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchingForm(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('cible', 'entity', array(
                    'class' => 'JDJCoreBundle:Cible',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                )
            )
            ->add('ageMin', 'integer', array(
                    'required' => false,
                )
            )
            ->add('joueurCount', 'integer', array(
                    'required' => false,
                )
            )
            ->add('duree', 'choice', array(
                    'choices' => array(
                        '-30' => 'moins de 30 minutes',
                        '30-60' => '30 à 60 minutes',
                        '60-120' => '1 à 2 heures',
                        '120-180' => '2 à 3 heures',
                        '180-' => 'plus de 3 heures',
                    ),
                    'required' => false,
                )
            )
            ->add('mechanism', 'entity', array(
                    'class' => 'JDJJeuBundle:Mechanism',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function(EntityRepository $repo) {
                        return $repo->createQueryBuilder('o')
                            ->orderBy('o.libelle');
                    }
                )
            )
            ->add('theme', 'entity', array(
                    'class' => 'JDJJeuBundle:Theme',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function(EntityRepository $repo) {
                        return $repo->createQueryBuilder('o')
                            ->orderBy('o.libelle');
                    }
                )
            )
            ->add('submit', 'submit', array()
            )
            ->getForm();

        return $form;
    }
} 