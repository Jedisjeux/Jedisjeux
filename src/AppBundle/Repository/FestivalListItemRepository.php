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

use AppBundle\Entity\Customer;
use AppBundle\Entity\FestivalList;
use AppBundle\Entity\ProductList;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class FestivalListItemRepository extends EntityRepository
{
    /**
     * @param FestivalList $list
     * @param string $locale
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilderByList(FestivalList $list, $locale)
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
            ->andWhere('list = :list')
            ->setParameter('locale', $locale)
            ->setParameter('main', true)
            ->setParameter('list', $list);

        return $queryBuilder;
    }
}
