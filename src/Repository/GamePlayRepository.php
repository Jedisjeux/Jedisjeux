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

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayRepository extends EntityRepository
{
    /**
     * @param $productId
     */
    public function findLatestByProductId($productId, int $count): array
    {
        return $this->createQueryBuilder('o')
            // with comments only
            ->innerJoin('o.topic', 'topic')
            ->andWhere('o.product = :productId')
            ->setParameter('productId', $productId)
            ->addOrderBy('o.createdAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return array{gamePlayCount: int, productCount: int}
     */
    public function countByAuthorBetween(int $authorId, \DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $result = $this->createAuthorBetweenQueryBuilder($authorId, $startDate, $endDate)
            ->select('COUNT(o.id) AS gamePlayCount')
            ->addSelect('COUNT(DISTINCT o.product) AS productCount')
            ->getQuery()
            ->getSingleResult();

        return [
            'gamePlayCount' => (int) $result['gamePlayCount'],
            'productCount' => (int) $result['productCount'],
        ];
    }

    /**
     * @return list<array{name: string, slug: string, imagePath: string|null, gamePlayCount: int}>
     */
    public function findMostPlayedByAuthorBetween(
        int $authorId,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate,
        string $locale,
    ): array {
        $rows = $this->createAuthorBetweenQueryBuilder($authorId, $startDate, $endDate)
            ->select('productTranslation.name AS name')
            ->addSelect('productTranslation.slug AS slug')
            ->addSelect('MAX(image.path) AS imagePath')
            ->addSelect('COUNT(DISTINCT o.id) AS gamePlayCount')
            ->join('o.product', 'product')
            ->join('product.translations', 'productTranslation', Join::WITH, 'productTranslation.locale = :locale')
            ->leftJoin('product.variants', 'variant', Join::WITH, 'variant.position = 0')
            ->leftJoin('variant.images', 'image', Join::WITH, 'image.main = :main')
            ->setParameter('locale', $locale)
            ->setParameter('main', true)
            ->groupBy('product.id')
            ->addGroupBy('productTranslation.name')
            ->addGroupBy('productTranslation.slug')
            ->orderBy('gamePlayCount', 'DESC')
            ->addOrderBy('productTranslation.name', 'ASC')
            ->getQuery()
            ->getArrayResult();

        return array_map(static fn (array $row): array => [
            'name' => (string) $row['name'],
            'slug' => (string) $row['slug'],
            'imagePath' => null === $row['imagePath'] ? null : 'uploads/img/'.$row['imagePath'],
            'gamePlayCount' => (int) $row['gamePlayCount'],
        ], $rows);
    }

    /**
     * @return array<int, int> Game play count indexed by month number (1 to 12)
     */
    public function countByMonthForAuthorBetween(int $authorId, \DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        // Grouped by day (playedAt is a date) then by month in PHP, as DQL has no MONTH() function here
        $rows = $this->createAuthorBetweenQueryBuilder($authorId, $startDate, $endDate)
            ->select('o.playedAt AS playedAt')
            ->addSelect('COUNT(o.id) AS gamePlayCount')
            ->groupBy('o.playedAt')
            ->getQuery()
            ->getArrayResult();

        $months = array_fill(1, 12, 0);

        foreach ($rows as $row) {
            $months[(int) $row['playedAt']->format('n')] += (int) $row['gamePlayCount'];
        }

        return $months;
    }

    /**
     * @return list<int> Years with at least one game play, most recent first
     */
    public function findPlayedYearsByAuthor(int $authorId): array
    {
        $result = $this->createQueryBuilder('o')
            ->select('MIN(o.playedAt) AS firstPlayedAt')
            ->addSelect('MAX(o.playedAt) AS lastPlayedAt')
            ->andWhere('o.author = :author')
            ->setParameter('author', $authorId)
            ->getQuery()
            ->getSingleResult();

        if (null === $result['firstPlayedAt'] || null === $result['lastPlayedAt']) {
            return [];
        }

        return range(
            (int) (new \DateTime($result['lastPlayedAt']))->format('Y'),
            (int) (new \DateTime($result['firstPlayedAt']))->format('Y'),
        );
    }

    private function createAuthorBetweenQueryBuilder(
        int $authorId,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate,
    ): QueryBuilder {
        return $this->createQueryBuilder('o')
            ->andWhere('o.author = :author')
            ->andWhere('o.playedAt >= :startDate')
            ->andWhere('o.playedAt < :endDate')
            ->setParameter('author', $authorId)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        return $this->createQueryBuilder('o')
            ->select('o', 'product', 'variant', 'productTranslation', 'image', 'topic', 'article')
            ->join('o.product', 'product')
            ->join('product.variants', 'variant')
            ->join('product.translations', 'productTranslation')
            ->leftJoin('variant.images', 'image')
            ->leftJoin('o.topic', 'topic')
            ->leftJoin('topic.article', 'article');
    }

    /**
     * @param string      $locale
     * @param string|null $authorId
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder($locale, $authorId = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->distinct()
            ->addSelect('product')
            ->addSelect('variant')
            ->addSelect('productTranslation')
            ->addSelect('image')
            ->addSelect('topic')
            ->addSelect('article')
            ->addSelect('players')
            ->addSelect('author')
            ->join('o.product', 'product')
            ->join('o.author', 'author')
            ->join('product.variants', 'variant', Join::WITH, 'variant.position = 0')
            ->join('product.translations', 'productTranslation')
            ->leftJoin('variant.images', 'image', Join::WITH, 'image.main = :main')
            ->leftJoin('o.topic', 'topic')
            ->leftJoin('topic.article', 'article')
            ->leftJoin('o.players', 'players')
            ->andWhere('productTranslation.locale = :locale')
            ->setParameter('locale', $locale)
            ->setParameter('main', true);

        if ($authorId) {
            $queryBuilder
                ->andWhere('author = :author')
                ->setParameter('author', $authorId);
        }

        return $queryBuilder;
    }

    /**
     * @param string $locale
     *
     * @return QueryBuilder
     */
    public function createFrontendListQueryBuilder($locale, array $criteria = [], string $productSlug = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->distinct()
            ->addSelect('product')
            ->addSelect('variant')
            ->addSelect('productTranslation')
            ->addSelect('image')
            ->addSelect('topic')
            ->addSelect('article')
            ->join('o.product', 'product')
            ->join('product.variants', 'variant', Join::WITH, 'variant.position = 0')
            ->join('product.translations', 'productTranslation')
            ->leftJoin('variant.images', 'image', Join::WITH, 'image.main = :main')
            ->leftJoin('o.topic', 'topic')
            ->leftJoin('topic.article', 'article')
            ->andWhere('productTranslation.locale = :locale')
            ->setParameter('locale', $locale)
            ->setParameter('main', true);

        if (isset($criteria['product'])) {
            $queryBuilder
                ->andWhere('product = :product')
                ->setParameter('product', $criteria['product']);
        }

        // game play with comments by default
        if (!isset($criteria['topic'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->isNotNull('topic.id'));
        }

        if (null !== $productSlug) {
            $queryBuilder
                ->andWhere('productTranslation.slug = :productSlug')
                ->setParameter('productSlug', $productSlug);
        }

        return $queryBuilder;
    }

    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        if (array_key_exists('hasTopic', $criteria)) {
            if ($criteria['hasTopic']) {
                $queryBuilder
                    ->andWhere('o.topic is not null');
            }
            unset($criteria['hasTopic']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

    /**
     * @param array $criteria
     * @param array $sorting
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function createFilterPaginator($criteria = [], $sorting = [])
    {
        $queryBuilder = $this->getQueryBuilder();

        if (empty($sorting)) {
            $sorting = [
                'createdAt' => 'desc',
            ];
        }

        $this->applyCriteria($queryBuilder, (array) $criteria);
        $this->applySorting($queryBuilder, (array) $sorting);

        return $this->getPaginator($queryBuilder);
    }
}
