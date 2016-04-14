<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 01/03/2016
 * Time: 12:58
 */

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use JDJ\CoreBundle\Entity\EntityRepository;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicRepository extends EntityRepository
{
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
        $queryBuilder = $this->getCollectionQueryBuilder();
        $queryBuilder
            ->addSelect('user')
            ->addSelect('customer')
            ->addSelect('avatar')
            ->join('o.createdBy', 'user')
            ->join('user.customer', 'customer')
            ->leftJoin('customer.avatar', 'avatar');

        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, (array)$sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Create paginator for products categorized under given taxon.
     *
     * @param TaxonInterface $taxon
     * @param array $criteria
     *
     * @return Pagerfanta
     */
    public function createByTaxonPaginator(TaxonInterface $taxon, array $criteria = array(), array $sorting = array())
    {
        $queryBuilder = $this->getCollectionQueryBuilder();
        $queryBuilder
            ->innerJoin($this->getAlias() . '.mainTaxon', 'taxon')
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
}