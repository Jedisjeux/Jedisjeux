<?php

/*
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\ProductInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;

class CustomerRepository extends EntityRepository
{
    /**
     * @param string $username
     *
     * @return CustomerInterface|null
     *
     * @throws NonUniqueResultException
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
    public function findLatest($count): array
    {
        return $this->createQueryBuilder('o')
            ->addSelect('user')
            ->leftJoin('o.user', 'user')
            ->addOrderBy('o.createdAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    public function findSubscribersToProductForOption(ProductInterface $product, string $option): array
    {
        return $this->createQueryBuilder('o')
            ->addSelect('user')
            ->leftJoin('o.user', 'user')
            ->leftJoin(
                'o.productSubscriptions',
                'product_subscription',
                Join::WITH,
                'product_subscription.subject = :product')
            ->where('product_subscription.options like :option')
            ->setParameter('product', $product)
            ->setParameter('option', '%"'.$option.'"%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function createListQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('user')
            ->join('o.user', 'user');

        return $queryBuilder;
    }
}
