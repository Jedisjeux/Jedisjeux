<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 20/09/2014
 * Time: 14:16
 */

namespace JDJ\SearchBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Person;
use AppBundle\Entity\Product;
use AppBundle\Entity\Topic;
use Elastica\Query\QueryString;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use JDJ\LudographieBundle\Entity\Personne;
use JDJ\UserBundle\Entity\User;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\User\Model\CustomerInterface;
use Sylius\Component\User\Model\UserInterface;
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

            if ($entity instanceof Product) {
                $result = array(
                    'value' => $entity->getName(),
                    'label' => $entity->getName(),
                    'image' => (null === $entity->getMainImage()) ? null : $this->get('liip_imagine.cache.manager')->getBrowserPath($entity->getMainImage()->getWebPath(), 'thumbnail'),
                    'href' => $this->generateUrl('sylius_product_show', array(
                            'slug' => $entity->getSlug(),
                        )
                    ),
                );
            }

            if ($entity instanceof TaxonInterface) {
                $result = array(
                    'value' => $entity->getName(),
                    'label' => $entity->getName(),
                    'image' =>  "//ssl.gstatic.com/accounts/ui/avatar_2x.png",
                    'href' => $this->generateUrl('sylius_product_index_by_taxon', array(
                            'permalink' => $entity->getPermalink(),
                        )
                    ),
                );
            }

            if ($entity instanceof Topic) {
                $result = array(
                    'value' => $entity->getTitle(),
                    'label' => $entity->getTitle(),
                    'image' =>  (null === $entity->getCreatedBy()->getCustomer()->getAvatar()) ? "//ssl.gstatic.com/accounts/ui/avatar_2x.png" : $this->get('liip_imagine.cache.manager')->getBrowserPath($entity->getCreatedBy()->getCustomer()->getAvatar()->getWebPath(), 'thumbnail'),
                    'href' => $entity->getGamePlay() ? $this->generateUrl('app_game_play_show', array(
                            'productSlug' => $entity->getGamePlay()->getProduct()->getSlug(),
                            'id' => $entity->getGamePlay()->getId(),
                        )
                    ) : $this->generateUrl('app_post_index_by_topic', array(
                            'topicId' => $entity->getId(),
                        )
                    ),
                );
            }

            if ($entity instanceof UserInterface) {
                $result = array(
                    'value' => $entity->getUsername(),
                    'label' => $entity->getUsername(),
                    'image' => (null === $entity->getCustomer()->getAvatar()) ? "//ssl.gstatic.com/accounts/ui/avatar_2x.png" : $this->get('liip_imagine.cache.manager')->getBrowserPath($entity->getCustomer()->getAvatar()->getWebPath(), 'thumbnail'),
                    'href' => "#",
                );
            }

            if ($entity instanceof Person) {
                $result = array(
                    'value' => (string)$entity,
                    'label' => (string)$entity,
                    'image' => (null === $entity->getMainImage()) ? "//ssl.gstatic.com/accounts/ui/avatar_2x.png" : $this->get('liip_imagine.cache.manager')->getBrowserPath($entity->getMainImage()->getWebPath(), 'thumbnail'),
                    'href' => $this->generateUrl('app_person_show', array(
                            'slug' => $entity->getSlug(),
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
            'title',
            'name',
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
        $finder = $this->container->get('fos_elastica.finder.jedisjeux');

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
            return $this->redirect($this->generateUrl('sylius_product_show', array(
                    'slug' => $current->getSlug(),
                )
            ));
        }

        if ($current instanceof TaxonInterface) {
            return $this->redirect($this->generateUrl('sylius_product_index_by_taxon', array(
                    'permalink' => $current->getPermalink(),
                )
            ));
        }

        if ($current instanceof Personne) {
            return $this->redirect($this->generateUrl('personne_show', array(
                    'id' => $current->getId(),
                    'slug' => $current->getSlug(),
                )
            ));
        }
    }
} 