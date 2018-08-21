<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Article;
use Doctrine\ORM\Query\Expr\Join;
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
     * @param string $localeCode
     * @param null|int $productId
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder(string $localeCode, $productId): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('mainImage')
            ->addSelect('mainTaxon')
            ->addSelect('taxonTranslation')
            ->leftJoin('o.mainImage', 'mainImage')
            ->leftJoin('o.mainTaxon', 'mainTaxon')
            ->leftJoin('mainTaxon.translations', 'taxonTranslation', Join::WITH, 'taxonTranslation.locale = :localeCode')
            ->setParameter('localeCode', $localeCode);

        if (null !== $productId) {
            $queryBuilder
                ->andWhere('o.product = :productId')
                ->setParameter('productId', $productId);
        }

        return $queryBuilder;
    }

    /**
     * @param string $localeCode
     * @param string|null $taxonSlug
     *
     * @return QueryBuilder
     */
    public function createFrontendListQueryBuilder(string $localeCode, string $taxonSlug = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('mainImage')
            ->addSelect('mainTaxon')
            ->addSelect('taxonTranslation')
            ->andWhere($queryBuilder->expr()->eq($this->getPropertyName('status'), ':status'))
            ->leftJoin('o.mainImage', 'mainImage')
            ->leftJoin('o.mainTaxon', 'mainTaxon')
            ->leftJoin('mainTaxon.translations', 'taxonTranslation', Join::WITH, 'taxonTranslation.locale = :localeCode')
            ->setParameter('localeCode', $localeCode)
            ->setParameter('status', Article::STATUS_PUBLISHED);

        if (null !== $taxonSlug) {
            $queryBuilder
                ->andWhere('taxonTranslation.slug = :taxonSlug')
                ->setParameter('taxonSlug', $taxonSlug);
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

    /**
     * @return int
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNbResults()
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->select($queryBuilder->expr()->count('o'))
            ->where($queryBuilder->expr()->eq($this->getPropertyName('status'), ':status'))
            ->setParameter('status', Article::STATUS_PUBLISHED);
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
