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
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->select('o, topic, product, productVariant, productTranslation')
            ->leftJoin('o.topic', 'topic')
            ->leftJoin('o.product', 'product')
            ->leftJoin('product.variants', 'productVariant')
            ->leftJoin('product.translations', 'productTranslation');

        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = [])
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
            ->innerJoin('o.mainTaxon', 'taxon')
            ->innerJoin('taxon.translations', 'taxonTranslation');

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
     * @param bool $publishable
     *
     * @return Pagerfanta
     */
    public function createByTaxonPaginator(TaxonInterface $taxon, array $criteria = null, array $sorting = null, $publishable = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->innerJoin('o.mainTaxon', 'taxon')
            ->andWhere($queryBuilder->expr()->orX(
                'taxon = :taxon',
                ':left < taxon.left AND taxon.right < :right'
            ))
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight());

        if ($publishable) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq($this->getPropertyName('publishable'), ':publishable'))
                ->setParameter('publishable', 1);
        }

        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, (array)$sorting);

        return $this->getPaginator($queryBuilder);
    }
}
