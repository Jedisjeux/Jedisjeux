<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 01/03/2016
 * Time: 12:58
 */

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        return $this
            ->createQueryBuilder('o')
            ->addSelect('user')
            ->addSelect('customer')
            ->addSelect('avatar')
            ->addSelect('article')
            ->addSelect('gamePlay')
            ->join('o.author', 'customer')
            ->join('customer.user', 'user')
            ->leftJoin('customer.avatar', 'avatar')
            ->leftJoin('o.article', 'article')
            ->leftJoin('o.gamePlay', 'gamePlay');
    }

    /**
     * {@inheritdoc}
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = [])
    {
        if (isset($sorting['postCount'])) {

            $queryBuilder
                ->addSelect("SIZE(" . $this->getPropertyName('posts') . " as HIDDEN postCount")
                ->addOrderBy('postCount', $sorting['postCount']);
            unset($sorting['postCount']);
        }

        parent::applySorting($queryBuilder, $sorting);
    }

    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator($criteria = array(), $sorting = array())
    {
        $queryBuilder = $this->getQueryBuilder();

        if ($criteria['product']) {

            $queryBuilder
                ->where($queryBuilder->expr()->orX(
                    'article.product = :product',
                    'gamePlay.product = :product'
                ))
                ->setParameter('product', $criteria['product']);

            unset($criteria['product']);
        }

        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, (array)$sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Create paginator for topics categorized under given taxon.
     *
     * @param TaxonInterface $taxon
     * @param array $criteria
     *
     * @return Pagerfanta
     */
    public function createByTaxonPaginator(TaxonInterface $taxon, array $criteria = array(), array $sorting = array())
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->innerJoin('o.mainTaxon', 'taxon')
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
     * Count topics categorized under given taxon.
     *
     * @param TaxonInterface $taxon
     *
     * @return Pagerfanta
     */
    public function countByTaxon(TaxonInterface $taxon)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->select('count(o)')
            ->innerJoin('o.mainTaxon', 'taxon')
            ->andWhere($queryBuilder->expr()->orX(
                'taxon = :taxon',
                ':left < taxon.left AND taxon.right < :right'
            ))
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight());

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}