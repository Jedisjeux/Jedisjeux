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

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CustomerRepository extends EntityRepository
{
    /**
     * @param string $username
     *
     * @return null|CustomerInterface
     */
    public function findOneByUsername(string $username): ?CustomerInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('user')
            ->join('o.user', 'user')
            ->where('user.usernameCanonical = :username')
            ->setParameter('username', $username);

        return $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $count
     *
     * @return array
     */
    public function findLatest($count)
    {
        return $this->createQueryBuilder('o')
            ->addSelect('user')
            ->leftJoin('o.user', 'user')
            ->addOrderBy('o.createdAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function createListQueryBuilder()
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('user')
            ->join('o.user', 'user');

        return $queryBuilder;
    }
}
