<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 11/04/2016
 * Time: 13:18
 */

namespace AppBundle\Repository;

use Sylius\Bundle\UserBundle\Doctrine\ORM\UserRepository as BaseUserRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UserRepository extends BaseUserRepository
{
    /**
     * @return int
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNbResults()
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->select($queryBuilder->expr()->count($this->getAlias()))
            ->andWhere($queryBuilder->expr()->eq($this->getPropertyName('enabled'), ':enabled'))
            ->setParameter('enabled', '1');
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}