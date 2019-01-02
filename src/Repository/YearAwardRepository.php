<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class YearAwardRepository extends EntityRepository
{
    /**
     * @param string $locale
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder($locale, string $awardSlug = null)
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
            ->andWhere('translation.locale = :locale')
            ->setParameter('locale', $locale)
            ->setParameter('main', true);

        if (null !== $awardSlug) {
            $queryBuilder
                ->innerJoin('o.award', 'award')
                ->andWhere('award.slug = :award')
                ->setParameter('award', $awardSlug);
        }

        return $queryBuilder;
    }
}