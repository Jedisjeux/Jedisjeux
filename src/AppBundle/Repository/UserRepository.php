<?php

/*
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use Sylius\Bundle\UserBundle\Doctrine\ORM\UserRepository as BaseUserRepository;
use Sylius\Component\User\Model\UserInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UserRepository extends BaseUserRepository
{
    /**
     * {@inheritdoc}
     */
    public function findOneByEmail(string $email): ?UserInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->leftJoin('o.customer', 'customer')
            ->andWhere($queryBuilder->expr()->eq('customer.emailCanonical', ':email'))
            ->setParameter('email', $email)
        ;

        return $queryBuilder
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

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