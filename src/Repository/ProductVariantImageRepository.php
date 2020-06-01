<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class ProductVariantImageRepository extends EntityRepository
{
    public function findLatest(int $count): array
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->join('o.variant', 'variant')
            ->where('o.main = :main')
            ->orderBy('variant.createdAt', 'desc')
            ->setMaxResults($count)
            ->setParameter('main', true)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    public function findLatestByPersonId(int $personId, int $count): array
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->join('o.variant', 'variant')
            ->leftJoin('variant.designers', 'designer', Join::WITH, 'designer.id = :person')
            ->leftJoin('variant.artists', 'artist', Join::WITH, 'artist.id = :person')
            ->leftJoin('variant.publishers', 'publisher', Join::WITH, 'publisher.id = :person')
            ->where('o.main = :main')
            ->andWhere($queryBuilder->expr()->orX(
                'designer.id is not null',
                'artist.id is not null',
                'publisher.id is not null'
            ))
            ->orderBy('variant.createdAt', 'desc')
            ->setMaxResults($count)
            ->setParameter('main', true)
            ->setParameter('person', $personId)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    public function findLatestByTaxonId(int $taxonId, int $count): array
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->join('o.variant', 'variant')
            ->join('variant.product', 'product')
            ->join('product.taxons', 'taxon')
            ->where('o.main = :main')
            ->andWhere('taxon = :taxon')
            ->orderBy('variant.createdAt', 'desc')
            ->setMaxResults($count)
            ->setParameter('main', true)
            ->setParameter('taxon', $taxonId)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
