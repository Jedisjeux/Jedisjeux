<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 05/06/2015
 * Time: 10:11
 */

namespace JDJ\ComptaBundle\Entity\Repository;


use Doctrine\ORM\QueryBuilder;
use JDJ\ComptaBundle\Entity\Subscription;
use JDJ\CoreBundle\Entity\EntityRepository;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class SubscriptionRepository extends EntityRepository
{
    /**
     * @inheritdoc
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = null)
    {
        if (isset($criteria['query'])) {
            $this
                ->joinTo($queryBuilder, 'customer', 'customer')
                ->joinTo($queryBuilder, 'billProduct', 'billProduct')
                ->joinTo($queryBuilder, 'billProduct.product', 'product');
            $queryBuilder
                ->andWhere('customer.society like :query or product.name like :query')
                ->setParameter('query', '%'.$criteria['query'].'%');
            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

    /**
     * @inheritdoc
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = null)
    {
        if (isset($sorting['product'])) {
            $this
                ->joinTo($queryBuilder, 'billProduct', 'billProduct')
                ->joinTo($queryBuilder, 'billProduct.product', 'product');
            $queryBuilder
                ->addOrderBy('product.name', $sorting['product']);
            unset($sorting['product']);
        }

        if (isset($sorting['society'])) {
            $this
                ->joinTo($queryBuilder, 'customer', 'customer');
            $queryBuilder
                ->addOrderBy('customer.society', $sorting['society']);
            unset($sorting['society']);
        }

        if (isset($sorting['paidAt'])) {
            $this
                ->joinTo($queryBuilder, 'billProduct', 'billProduct')
                ->joinTo($queryBuilder, 'billProduct.bill', 'bill');
            $queryBuilder
                ->addOrderBy('bill.paidAt', $sorting['paidAt']);
            unset($sorting['paidAt']);
        }

        parent::applySorting($queryBuilder, $sorting);
    }

    /**
     * @return \Pagerfanta\Pagerfanta
     */
    public function findEndingSubscriptions() {
        $queryBuilder = $this->getQueryBuilder();

        $this->applyCriteria($queryBuilder, array(
            'status' => Subscription::IN_PROGRESS,
        ));

        $today = new \DateTime();

        $queryBuilder
            ->andWhere($queryBuilder->expr()->lt($this->getAlias() . '.endAt', ':today'))
            ->setParameter('today', $today->format('Y-m-d'));

        return $this->getPaginator($queryBuilder);
    }
}