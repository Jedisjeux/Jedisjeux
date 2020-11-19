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

use App\Entity\ProductFile;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class ProductFileRepository extends EntityRepository
{
    public function createListQueryBuilder(string $localeCode): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('product')
            ->addSelect('productTranslation')
            ->addSelect('variant')
            ->addSelect('image')
            ->innerJoin('o.product', 'product')
            ->innerJoin('product.translations', 'productTranslation')
            ->innerJoin('product.variants', 'variant', Join::WITH, 'variant.position = 0')
            ->leftJoin('variant.images', 'image', Join::WITH, 'image.main = :main')
            ->andWhere('productTranslation.locale = :locale')
            ->setParameter('locale', $localeCode)
            ->setParameter('main', true)
        ;

        return $queryBuilder;
    }

    /**
     * @param $productId
     */
    public function findLatestByProductId($productId, int $count): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.product = :productId')
            ->andWhere('o.status = :status')
            ->setParameter('productId', $productId)
            ->setParameter('status', ProductFile::STATUS_ACCEPTED)
            ->addOrderBy('o.updatedAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
            ;
    }

    public function createListForProductSlugQueryBuilder(string $localeCode, string $productSlug): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('product')
            ->addSelect('translation')
            ->join('o.product', 'product')
            ->join('product.translations', 'translation')
            ->andWhere('translation.locale = :locale')
            ->andWhere('translation.slug = :slug')
            ->andWhere('o.status = :status')
            ->setParameter('locale', $localeCode)
            ->setParameter('slug', $productSlug)
            ->setParameter('status', ProductFile::STATUS_ACCEPTED)
        ;

        return $queryBuilder;
    }
}
