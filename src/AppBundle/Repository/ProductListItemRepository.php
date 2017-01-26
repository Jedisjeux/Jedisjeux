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

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductListItemRepository extends EntityRepository
{
    /**
     * @param string $productListSlug
     * @param string $locale
     *
     * @return QueryBuilder
     */
    public function createQueryBuilderByProductList($productListSlug, $locale)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('product')
            ->addSelect('translation')
            ->join('o.product', 'product')
            ->leftJoin('product.translations', 'translation')
            ->innerJoin('o.list', 'list')
            ->andWhere('translation.locale = :locale')
            ->andWhere('list.slug = :productListSlug')
            ->setParameter('locale', $locale)
            ->setParameter('productListSlug', $productListSlug);

        return $queryBuilder;
    }
}
