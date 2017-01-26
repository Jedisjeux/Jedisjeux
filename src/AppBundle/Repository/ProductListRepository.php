<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductListRepository extends EntityRepository
{
    /**
     * @param int|CustomerInterface $owner
     * @param int|ProductInterface $product
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
