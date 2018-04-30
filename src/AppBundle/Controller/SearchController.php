<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Person;
use AppBundle\Entity\Product;
use AppBundle\Entity\Topic;
use Elastica\Query\QueryString;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @author Loïc Frémont <loic@mobizel.com>
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

        $query = $this->createFindByTermQuery($term);

        /** @var TransformedFinder $finder */
        $finder = $this->get('fos_elastica.finder.jedisjeux');
        $paginator = $finder->findPaginated(Query::create($query));
        $paginator->setMaxPerPage(16);
        $paginator->setCurrentPage($request->get('page', 1));

        if ($paginator->getNbResults() === 1) {
            $this->redirectToTheUniqueResult($paginator);
        }

        $types = $this->getTypesOfResults($paginator);

        return $this->render('frontend/search/index.html.twig', array(
            'results' => $paginator,
            'types' => $types,
        ));
    }

    /**
     * @param string $term
     *
     * @return Query
     */
    protected function createFindByTermQuery($term)
    {
        $boolQuery = new Query\BoolQuery();

        $queryString = $this->getQueryStringByTerm($term);
        $boolQuery->addMust($queryString);
        $this->excludeUnpublishedStatuses($boolQuery);

        return new Query($boolQuery);
    }

    /**
     * @param Query\BoolQuery $boolQuery
     */
    protected function excludeUnpublishedStatuses(Query\BoolQuery $boolQuery)
    {
        $newStatus = new Query\Term();
        $newStatus->setTerm('status', 'new');
        $boolQuery->addMustNot($newStatus);

        $pendingTranslationStatus = new Query\Term();
        $pendingTranslationStatus->setTerm('status', 'pending_translation');
        $boolQuery->addMustNot($pendingTranslationStatus);

        $pendingReviewStatus = new Query\Term();
        $pendingReviewStatus->setTerm('status', 'pending_review');
        $boolQuery->addMustNot($pendingReviewStatus);

        $pendingPublicationStatus = new Query\Term();
        $pendingPublicationStatus->setTerm('status', 'pending_publication');
        $boolQuery->addMustNot($pendingPublicationStatus);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function autoCompleteAction(Request $request)
    {
        $term = $request->get("term", "");

        $query = $this->createFindByTermQuery($term);

        /** @var TransformedFinder $finder */
        $finder = $this->get('fos_elastica.finder.jedisjeux');
        $paginator = $finder->findPaginated(Query::create($query));

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
                    'href' => $this->generateUrl('sylius_frontend_product_show', array(
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
                    'href' => $this->generateUrl('sylius_frontend_product_index_by_taxon', array(
                            'permalink' => $entity->getPermalink(),
                        )
                    ),
                );
            }

            if ($entity instanceof Topic) {
                $result = array(
                    'value' => $entity->getTitle(),
                    'label' => $entity->getTitle(),
                    'image' =>  (null === $entity->getAuthor()->getAvatar()) ? "//ssl.gstatic.com/accounts/ui/avatar_2x.png" : $this->get('liip_imagine.cache.manager')->getBrowserPath($entity->getAuthor()->getAvatar()->getWebPath(), 'thumbnail'),
                    'href' => $entity->getGamePlay() ? $this->generateUrl('app_frontend_game_play_show', array(
                            'productSlug' => $entity->getGamePlay()->getProduct()->getSlug(),
                            'id' => $entity->getGamePlay()->getId(),
                        )
                    ) : $this->generateUrl('app_frontend_post_index_by_topic', array(
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
                    'href' => $this->generateUrl('sylius_frontend_user_show', [
                        'username' => $entity->getUsernameCanonical()
                    ]),
                );
            }

            if ($entity instanceof Person) {
                $result = array(
                    'value' => (string)$entity,
                    'label' => (string)$entity,
                    'image' => (null === $entity->getMainImage()) ? "//ssl.gstatic.com/accounts/ui/avatar_2x.png" : $this->get('liip_imagine.cache.manager')->getBrowserPath($entity->getMainImage()->getWebPath(), 'thumbnail'),
                    'href' => $this->generateUrl('app_frontend_person_show', array(
                            'slug' => $entity->getSlug(),
                        )
                    ),
                );
            }

            if ($entity instanceof Article) {
                $result = array(
                    'value' => (string)$entity,
                    'label' => (string)$entity,
                    'image' => (null === $entity->getMainImage()) ? "//ssl.gstatic.com/accounts/ui/avatar_2x.png" : $this->get('liip_imagine.cache.manager')->getBrowserPath($entity->getMainImage()->getWebPath(), 'cmf_thumbnail'),
                    'href' => $this->generateUrl('app_frontend_article_show', array(
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
            'variants',
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
                $types[] = "game";
            } elseif ($result instanceof Person) {
                $types[] = "person";
            } elseif ($result instanceof UserInterface) {
                $types[] = "user";
            } elseif ($result instanceof Article) {
                $types[] = "article";
            } elseif ($result instanceof Topic) {
                $types[] = "topic";
            } else {
                $types[] = "";
            }

        }

        return $types;
    }

    /**
     * @param Pagerfanta $paginator
     *
     * @return RedirectResponse|null
     */
    private function redirectToTheUniqueResult(Pagerfanta $paginator): ?RedirectResponse
    {
        $current = $paginator->getIterator()->current();

        if ($current instanceof ProductInterface) {
            return $this->redirect($this->generateUrl('sylius_frontend_product_show', array(
                    'slug' => $current->getSlug(),
                )
            ));
        }

        if ($current instanceof TaxonInterface) {
            return $this->redirect($this->generateUrl('sylius_frontend_product_index_by_taxon', array(
                    'permalink' => $current->getPermalink(),
                )
            ));
        }

        if ($current instanceof Person) {
            return $this->redirect($this->generateUrl('app_frontend_person_show', array(
                    'slug' => $current->getSlug(),
                )
            ));
        }

        if ($current instanceof Topic) {
            return $this->redirect($this->generateUrl('app_frontend_post_index_by_topic', array(
                    'topicId' => $current->getId(),
                )
            ));
        }

        return null;
    }
}
