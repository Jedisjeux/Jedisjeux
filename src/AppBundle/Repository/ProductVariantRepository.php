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

use \Sylius\Bundle\ProductBundle\Doctrine\ORM\ProductVariantRepository as BaseProductVariantRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * TODO remove after sylius upgrade (here is alpha-1)
 */
class ProductVariantRepository extends BaseProductVariantRepository
{
    /**
     * {@inheritdoc}
     */
    public function createQueryBuilderByProductId($productId)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.product = :productId')
            ->setParameter('productId', $productId)
            ;
    }
}
