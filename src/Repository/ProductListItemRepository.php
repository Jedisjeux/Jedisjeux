<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\ProductBox;
use App\Entity\ProductList;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;

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
            ->addSelect('variant')
            ->addSelect('image')
            ->join('o.product', 'product')
            ->leftJoin('product.translations', 'translation')
            ->leftJoin('product.variants', 'variant', Join::WITH, 'variant.position = 0')
            ->leftJoin('variant.images', 'image', Join::WITH, 'image.main = :main')
            ->innerJoin('o.list', 'list')
            ->andWhere('translation.locale = :locale')
            ->andWhere('list.slug = :productListSlug')
            ->setParameter('locale', $locale)
            ->setParameter('main', true)
            ->setParameter('productListSlug', $productListSlug);

        return $queryBuilder;
    }

    /**
     * @param CustomerInterface $customer
     * @param string            $locale
     *
     * @return QueryBuilder
     */
    public function createQueryBuilderForGameLibrary(CustomerInterface $customer, $locale)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('product')
            ->addSelect('translation')
            ->addSelect('variant')
            ->addSelect('box')
            ->addSelect('boxImage')
            ->join('o.product', 'product')
            ->leftJoin('product.translations', 'translation')
            ->innerJoin('o.list', 'list')
            ->leftJoin('product.variants', 'variant')
            ->leftJoin('variant.box', 'box', Join::WITH, 'box.status = :status')
            ->leftJoin('box.image', 'boxImage')
            ->andWhere('translation.locale = :locale')
            ->andWhere('list.owner = :owner')
            ->andWhere('list.code = :code')
            ->groupBy('product.id')
            ->setParameter('status', ProductBox::STATUS_ACCEPTED)
            ->setParameter('locale', $locale)
            ->setParameter('owner', $customer)
            ->setParameter('code', ProductList::CODE_GAME_LIBRARY);

        return $queryBuilder;
    }
}
