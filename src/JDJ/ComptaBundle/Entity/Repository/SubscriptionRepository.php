<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 05/06/2015
 * Time: 10:11
 */

namespace JDJ\ComptaBundle\Entity\Repository;


use JDJ\ComptaBundle\Entity\Subscription;
use JDJ\CoreBundle\Entity\EntityRepository;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class SubscriptionRepository extends EntityRepository
{
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