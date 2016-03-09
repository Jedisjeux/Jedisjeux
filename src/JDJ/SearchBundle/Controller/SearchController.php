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
use JDJ\LudographieBundle\Entity\Personne;
use JDJ\UserBundle\Entity\User;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Product\Model\ProductInterface;
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
        $paginator->setMaxPerPage(5);
        $paginator->setCurrentPage($request->get('page', 1));

        $results = array();
        foreach ($paginator as $entity) {
            $result = array();

            if ($entity instanceof ProductInterface) {
                /** @var Jeu $jeu */
                $jeu = $entity;
                $result = array(
                    'value' => $jeu->getName(),
                    'label' => $jeu->getName(),
                    'image' => (null === $jeu->getImageCouverture()) ? null : $this->get('liip_imagine.cache.manager')->getBrowserPath($jeu->getImageCouverture()->getWebPath(), 'thumbnail'),
                    'href' => $this->generateUrl('jeu_show', array(
                            'id' => $jeu->getId(),
                            'slug' => $jeu->getSlug(),
                        )
                    ),
                );
            }

            if ($entity instanceof User) {
                /** @var User $user */
                $user = $entity;
                $result = array(
                    'value' => $user->getUsername(),
                    'label' => $user->getUsername(),
                    'image' => (null === $user->getAvatar()) ? "//ssl.gstatic.com/accounts/ui/avatar_2x.png" : $this->get('liip_imagine.cache.manager')->getBrowserPath($user->getAvatar()->getWebPath(), 'thumbnail'),
                    'href' => "#",
                );
            }

            if ($entity instanceof Personne) {
                /** @var Personne $personne */
                $personne = $entity;
                $result = array(
                    'value' => (string)$personne,
                    'label' => (string)$personne,
                    'image' => (null === $personne->getImage()) ? "//ssl.gstatic.com/accounts/ui/avatar_2x.png" : $this->get('liip_imagine.cache.manager')->getBrowserPath($personne->getImage()->getWebPath(), 'thumbnail'),
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
            if ($result instanceof ProductInterface) {
                $types[] = "jeu";
            } elseif ($result instanceof Personne) {
                $types[] = "personne";
            } elseif ($result instanceof User) {
                $types[] = "user";
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

        if ($current instanceof ProductInterface) {
            $jeu = $current;
            return $this->redirect($this->generateUrl('jeu_show', array(
                    'id' => $jeu->getId(),
                    'slug' => $jeu->getSlug(),
                )
            ));
        }

        if ($current instanceof Personne) {
            $personne = $current;
            return $this->redirect($this->generateUrl('personne_show', array(
                    'id' => $personne->getId(),
                    'slug' => $personne->getSlug(),
                )
            ));
        }
    }
} 