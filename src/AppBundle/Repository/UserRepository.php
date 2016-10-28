<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 11/04/2016
 * Time: 13:18
 */

namespace AppBundle\Repository;

use Sylius\Bundle\ApiBundle\Model\UserInterface;
use Sylius\Bundle\UserBundle\Doctrine\ORM\UserRepository as BaseUserRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UserRepository extends BaseUserRepository
{
    /**
     * @param string $role
     *
     * @return array|UserInterface[]
     */
    public function findByRole($role)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->addSelect('customer')
            ->leftJoin('o.customer', 'customer')
            ->where($queryBuilder->expr()->like('o.roles', ':role'))
            ->setParameter('role', '%"' . $role . '"%');

        return $queryBuilder->getQuery()->getResult();
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
            ->andWhere($queryBuilder->expr()->eq($this->getPropertyName('enabled'), ':enabled'))
            ->setParameter('enabled', '1');
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}