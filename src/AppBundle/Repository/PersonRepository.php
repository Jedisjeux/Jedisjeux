<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 14:11
 */

namespace AppBundle\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonRepository extends EntityRepository
{
    public function createListQueryBuilder(TaxonInterface $taxon = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('image')
            ->leftJoin('o.images', 'image', Join::WITH, 'image.main = 1')
            ->groupBy('o.id');

        $queryBuilder->addSelect($queryBuilder->expr()->sum(
                "o.productCountAsDesigner",
                "o.productCountAsPublisher",
                "o.productCountAsArtist") .
        " as HIDDEN gameCount");
        $queryBuilder->addOrderBy("gameCount", 'desc');

        if ($taxon) {
            $queryBuilder
                ->innerJoin('o.taxons', 'taxon')
                ->andWhere($queryBuilder->expr()->orX(
                    'taxon = :taxon',
                    ':left < taxon.left AND taxon.right < :right'
                ))
                ->setParameter('taxon', $taxon)
                ->setParameter('left', $taxon->getLeft())
                ->setParameter('right', $taxon->getRight());
        }

        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function findByNamePart($phrase)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.slug LIKE :name')
            ->setParameter('name', '%'.$phrase.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @inheritdoc
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere('o.slug like :query')
                ->setParameter('query', '%' . $criteria['query'] . '%');

            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

    /**
     * @param array $criteria
     * @param array $sorting
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator($criteria = [], $sorting = [])
    {
        $queryBuilder = $this->createQueryBuilder('o');

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = [];
            }

            $sorting['id'] = 'asc';
        }

        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * @inheritdoc
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = array())
    {
        if (isset($sorting['gameCount'])) {
            $queryBuilder->addSelect($queryBuilder->expr()->sum(
                    "o.productCountAsDesigner",
                    "o.productCountAsPublisher",
                    "o.productCountAsArtist") .
                " as HIDDEN gameCount");
            $queryBuilder->addOrderBy("gameCount", $sorting['gameCount']);
            unset($sorting['gameCount']);
        }

        parent::applySorting($queryBuilder, $sorting);
    }

    /**
     * Create paginator for products categorized under given taxon.
     *
     * @param TaxonInterface $taxon
     * @param array $criteria
     * @param array $sorting
     *
     * @return Pagerfanta
     */
    public function createByTaxonPaginator(TaxonInterface $taxon, array $criteria = [], array $sorting = [])
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->innerJoin('o.taxons', 'taxon')
            ->andWhere($queryBuilder->expr()->orX(
                'taxon = :taxon',
                ':left < taxon.left AND taxon.right < :right'
            ))
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight());

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

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
            ->select($queryBuilder->expr()->count('o'));
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}