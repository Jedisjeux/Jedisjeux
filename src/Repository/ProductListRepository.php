<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductListRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createListQueryBuilder()
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->join('o.owner', 'owner')
            ->leftJoin('owner.user', 'user');

        return $queryBuilder;
    }

    /**
     * @param int|CustomerInterface $owner
     * @param int|ProductInterface  $product
     *
     * @return array
     */
    public function findByOwnerAndProduct($owner, $product)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->join('o.owner', 'owner')
            ->andWhere('owner = :owner')
            ->setParameter('owner', $owner)
            ->join('o.items', 'item')
            ->join('item.product', 'product')
            ->andWhere('product = :product')
            ->setParameter('product', $product);

        return $queryBuilder->getQuery()->getResult();
    }
}
