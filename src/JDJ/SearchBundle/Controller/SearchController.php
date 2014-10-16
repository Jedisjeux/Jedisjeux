<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 20/09/2014
 * Time: 14:16
 */

namespace JDJ\SearchBundle\Controller;

use Elastica\Query\QueryString;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\JeuBundle\Entity\Mecanisme;
use JDJ\JeuBundle\Entity\Theme;
use JDJ\LudographieBundle\Entity\Personne;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class SearchController
 * @package JDJ\SearchBundle\Controller
 */
class SearchController extends Controller
{

    /**
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function searchQueryAction(Request $request)
    {
        $term = $request->get("query");

        $paginator = $this->findByQuery($this->getQueryStringByTerm($term));
        $paginator->setMaxPerPage(16);
        $paginator->setCurrentPage($request->get('page', 1));

        if ($paginator->getNbResults() === 1) {
            return $this->redirectToTheUniqueResult($paginator);
        }

        $types = $this->getTypesOfResults($paginator);

        return $this->render('JDJSearchBundle:Search:searchResults.html.twig', array(
            'results' => $paginator,
            'types' => $types,
        ));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function autoCompleteAction(Request $request)
    {
        $term = $request->get("term", "");

        $paginator = $this->findByQuery($this->getQueryStringByTerm($term));
        $paginator->setMaxPerPage(10);
        $paginator->setCurrentPage($request->get('page', 1));

        $results = array();
        foreach ($paginator as $entity) {
            $result = array();

            if ($entity instanceof Jeu) {
                /** @var Jeu $jeu */
                $jeu = $entity;
                $result = array(
                    'value' => $jeu->getLibelle(),
                    'label' => $jeu->getLibelle(),
                    'image' => "",
                    'category' => 'Jeux',
                    'description' => 'Jeux',
                    'href' => $this->generateUrl('jeu_show', array(
                            'id' => $jeu->getId(),
                            'slug' => $jeu->getSlug(),
                        )
                    ),
                );
            }

            if ($entity instanceof Mecanisme) {
                /** @var Mecanisme $mecanisme */
                $mecanisme = $entity;
                $result = array(
                    'value' => $mecanisme->getLibelle(),
                    'label' => $mecanisme->getLibelle(),
                    'image' => "",
                    'category' => 'Mecanismes',
                    'description' => 'Mecanismes',
                    'href' => $this->generateUrl('mecanisme_show', array(
                            'id' => $mecanisme->getId(),
                            'slug' => $mecanisme->getSlug(),
                        )
                    ),
                );
            }

            if ($entity instanceof Theme) {
                /** @var Theme $theme */
                $theme = $entity;
                $result = array(
                    'value' => $theme->getLibelle(),
                    'label' => $theme->getLibelle(),
                    'image' => "",
                    'category' => 'Thèmes',
                    'description' => 'Thèmes',
                    'href' => $this->generateUrl('theme_show', array(
                            'id' => $theme->getId(),
                            'slug' => $theme->getSlug(),
                        )
                    ),
                );
            }

            if ($entity instanceof Personne) {
                /** @var Personne $personne */
                $personne = $entity;
                $result = array(
                    'value' => (string)$personne,
                    'label' => (string)$personne,
                    'image' => "",
                    'category' => 'Thèmes',
                    'description' => 'Thèmes',
                    'href' => $this->generateUrl('personne_show', array(
                            'id' => $personne->getId(),
                            'slug' => $personne->getSlug(),
                        )
                    ),
                );
            }

            $results[] = $result;
        }

        return new JsonResponse($results);
    }

    /**
     * @param string $term
     * @return \Elastica\Query\QueryString
     */
    private function getQueryStringByTerm($term)
    {
        $searchQuery = new QueryString();
        $searchQuery->setParam('query', $term);

        $searchQuery->setDefaultOperator('AND');

        // execute a request of type "fields", with all theses following columns
        $searchQuery->setParam('fields', array(
            'slug',
            'libelle',
            'email',
            'username',
        ));

        return $searchQuery;
    }

    /**
     * @param $results
     * @return array
     */
    private function getTypesOfResults($results)
    {
        $types = array();
        foreach ($results as $result) {
            if ($result instanceof Jeu) {
                $types[] = "jeu";
            } elseif ($result instanceof Personne) {
                $types[] = "personne";
            } elseif ($result instanceof Mecanisme) {
                $types[] = "mecanisme";
            } elseif ($result instanceof Theme) {
                $types[] = "theme";
            }

        }
        return $types;
    }

    /**
     * @param $query
     * @return Pagerfanta
     */
    private function findByQuery($query)
    {
        /** @var TransformedFinder $finder */
        $finder = $this->container->get('fos_elastica.finder.search');

        /** @var Pagerfanta $userPaginator */
        $paginator = $finder->findPaginated($query);
        return $paginator;

    }

    /**
     * @param Pagerfanta $paginator
     * @return RedirectResponse
     */
    private function redirectToTheUniqueResult(Pagerfanta $paginator)
    {
        $current = $paginator->getIterator()->current();

        if ($current instanceof Jeu) {
            /** @var Jeu $jeu */
            $jeu = $current;
            return $this->redirect($this->generateUrl('jeu_show', array(
                    'id' => $jeu->getId(),
                    'slug' => $jeu->getSlug(),
                )
            ));
        }

        if ($current instanceof Personne) {
            /** @var Personne $personne */
            $personne = $current;
            return $this->redirect($this->generateUrl('personne_show', array(
                    'id' => $personne->getId(),
                    'slug' => $personne->getSlug(),
                )
            ));
        }

        if ($current instanceof Mecanisme) {
            /** @var Mecanisme $mecanisme */
            $mecanisme = $current;
            return $this->redirect($this->generateUrl('mecanisme_show', array(
                    'id' => $mecanisme->getId(),
                    'slug' => $mecanisme->getSlug(),
                )
            ));
        }

        if ($current instanceof Theme) {
            /** @var Theme $theme */
            $theme = $current;
            return $this->redirect($this->generateUrl('theme_show', array(
                    'id' => $theme->getId(),
                    'slug' => $theme->getSlug(),
                )
            ));
        }
    }
} 