<?php

/*
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Review\Model\ReviewInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class ProductReviewRepository extends EntityRepository
{
    /**
     * @param $productId
     * @param int $count
     *
     * @return array
     */
    public function findLatestByProductId($productId, int $count): array
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder
            ->andWhere('o.reviewSubject = :productId')
            ->andWhere('o.status = :status')
            ->andWhere($queryBuilder->expr()->isNotNull('o.comment'))
            ->setParameter('productId', $productId)
            ->setParameter('status', ReviewInterface::STATUS_ACCEPTED)
            ->addOrderBy('o.createdAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $personId
     * @param int $count
     *
     * @return array
     */
    public function findLatestByPersonId($personId, int $count): array
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder
            ->distinct()
            ->join('o.reviewSubject', 'product')
            ->leftJoin('product.variants', 'variant')
            ->leftJoin('variant.designers', 'designer')
            ->leftJoin('variant.artists', 'artist')
            ->leftJoin('variant.publishers', 'publisher')
            ->andWhere($queryBuilder->expr()->orX(
                'designer = :personId',
                'artist = :personId',
                'publisher = :personId'
            ))
            ->andWhere('o.status = :status')
            ->andWhere($queryBuilder->expr()->isNotNull('o.comment'))
            ->setParameter('personId', $personId)
            ->setParameter('status', ReviewInterface::STATUS_ACCEPTED)
            ->addOrderBy('o.createdAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string $localeCode
     * @param ProductInterface $product
     *
     * @return QueryBuilder
     */
    public function createListForProductQueryBuilder(string $localeCode, ProductInterface $product): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('o.reviewSubject', ':product'))
            ->setParameter('product', $product)
        ;

        return $queryBuilder;
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        return $this->createQueryBuilder('o')
            ->select('o, product, productVariant, productTranslation')
            ->join('o.reviewSubject', 'product')
            ->leftJoin('product.variants', 'productVariant')
            ->leftJoin('product.translations', 'productTranslation');
    }

    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        if (isset($criteria['hasComment']) && $criteria['hasComment'] !== '') {
            if ($criteria['hasComment']) {
                $queryBuilder
                    ->andWhere('o.comment is not null');
            } else {
                // Sylius entity repository handles this case
                $criteria['comment'] = null;
            }
            unset($criteria['hasComment']);
        }


        parent::applyCriteria($queryBuilder, $criteria);
    }

    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator($criteria = [], $sorting = [])
    {
        $queryBuilder = $this->getQueryBuilder()
            ->groupBy('o.id');

        if (isset($criteria['person'])) {
            $queryBuilder
                ->leftJoin('productVariant.designers', 'designer')
                ->leftJoin('productVariant.artists', 'artist')
                ->leftJoin('productVariant.publishers', 'publisher')
                ->andWhere($queryBuilder->expr()->orX(
                    'designer = :person',
                    'artist = :person',
                    'publisher = :person'
                ))
                ->setParameter('person', $criteria['person']);

            unset($criteria['person']);
        }

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = [];
            }

            $sorting['updatedAt'] = 'desc';
        }

        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * @return int
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNbResults()
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->select($queryBuilder->expr()->count('o'))
            ->where($queryBuilder->expr()->eq($this->getPropertyName('status'), ':status'))
            ->setParameter('status', ReviewInterface::STATUS_ACCEPTED);
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

}