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

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class ProductBoxRepository extends EntityRepository
{
    public function createListQueryBuilder(string $localeCode): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('product')
            ->addSelect('productTranslation')
            ->addSelect('productVariant')
            ->addSelect('variant')
            ->addSelect('image')
            ->innerJoin('o.product', 'product')
            ->innerJoin('product.translations', 'productTranslation')
            ->innerJoin('o.productVariant', 'productVariant')
            ->innerJoin('product.variants', 'variant', Join::WITH, 'variant.position = 0')
            ->leftJoin('variant.images', 'image', Join::WITH, 'image.main = :main')
            ->andWhere('productTranslation.locale = :locale')
            ->setParameter('locale', $localeCode)
            ->setParameter('main', true)
        ;

        return $queryBuilder;
    }
}
