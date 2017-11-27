<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Article;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleRepository extends EntityRepository
{
    /**
     * @param string $slug
     * @param bool $showUnpublished
     *
     * @return null|Article
     */
    public function findOneBySlug($slug, $showUnpublished = true)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->andWhere('o.slug = :slug')
            ->setParameter('slug', $slug);

        if (false === $showUnpublished) {
            $queryBuilder
                ->andWhere('o.status = :published')
                ->setParameter('published', Article::STATUS_PUBLISHED);
        }

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $count
     *
     * @return array
     */
    public function findLatest($count)
    {
        return $this->createQueryBuilder('o')
            ->addOrderBy('o.createdAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param null|int $productId
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder($productId = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->leftJoin('o.product', 'product')
            ->leftJoin('o.mainTaxon', 'mainTaxon');

        if (null !== $productId) {
            $queryBuilder
                ->andWhere('product = :productId')
                ->setParameter('productId', $productId);
        }

        return $queryBuilder;
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->select('o, topic, product, productVariant, productTranslation')
            ->addSelect('gamePlay')
            ->addSelect('mainImage')
            ->leftJoin('o.mainImage', 'mainImage')
            ->leftJoin('o.topic', 'topic')
            ->leftJoin('topic.gamePlay', 'gamePlay')
            ->leftJoin('o.product', 'product')
            ->leftJoin('product.variants', 'productVariant')
            ->leftJoin('product.translations', 'productTranslation')
            ->groupBy('o.id');

        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        if (isset($criteria['publishStartDateFrom'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->gte($this->getPropertyName('publishStartDate'), ':publishStartDateFrom'))
                ->setParameter('publishStartDateFrom', $criteria['publishStartDateFrom']);

            unset($criteria['publishStartDateFrom']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }


    /**
     * @param array|null $criteria
     * @param array|null $sorting
     * @param string|null $status
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator(array $criteria = null, array $sorting = null, $status = Article::STATUS_PUBLISHED)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->addSelect('taxon')
            ->addSelect('taxonTranslation')
            ->leftJoin('o.mainTaxon', 'taxon')
            ->leftJoin('taxon.translations', 'taxonTranslation');

        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like($this->getPropertyName('title'), ':query'))
                ->setParameter('query', '%' . $criteria['query'] . '%');

            unset($criteria['query']);
        }

        if (isset($criteria['person'])) {
            $queryBuilder
                ->leftJoin('productVariant.designers', 'designer')
                ->leftJoin('productVariant.artists', 'artist')
                ->leftJoin('productVariant.publishers', 'publisher')
                ->andWhere($queryBuilder->expr()->orX(
                    'designer = :person',
                    'artist = :person',
                    'publisher = :person'
                ))
                ->setParameter('person', $criteria['person']);

            unset($criteria['person']);
        }

        if (null !== $status) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq($this->getPropertyName('status'), ':status'))
                ->setParameter('status', $status);
        }

        if (!$sorting) {
            $sorting = [
                'publishStartDate' => 'desc',
            ];
        }


        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, (array)$sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Create paginator for products categorized under given taxon.
     *
     * @param TaxonInterface $taxon
     * @param array|null $criteria
     * @param array|null $sorting
     * @param string $status
     *
     * @return Pagerfanta
     */
    public function createByTaxonPaginator(TaxonInterface $taxon, array $criteria = null, array $sorting = null, $status = Article::STATUS_PUBLISHED)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->addSelect('taxon')
            ->addSelect('taxonTranslation')
            ->leftJoin('o.mainTaxon', 'taxon')
            ->leftJoin('taxon.translations', 'taxonTranslation')
            ->andWhere($queryBuilder->expr()->orX(
                'taxon = :taxon',
                ':left < taxon.left AND taxon.right < :right'
            ))
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight());

        if (null !== $status) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq($this->getPropertyName('status'), ':status'))
                ->setParameter('status', $status);
        }

        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, (array)$sorting);

        return $this->getPaginator($queryBuilder);
    }
}
