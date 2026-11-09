<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class StatisticsProvider
{
    /** @var ArticleRepository */
    private RepositoryInterface $articleRepository;

    /** @var ProductRepository */
    private RepositoryInterface $productRepository;

    /** @var UserRepository */
    private RepositoryInterface $appUserRepository;

    public function __construct(
        RepositoryInterface $articleRepository,
        RepositoryInterface $productRepository,
        private RepositoryInterface $personRepository,
        RepositoryInterface $appUserRepository,
        private RepositoryInterface $productReviewRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->productRepository = $productRepository;
        $this->appUserRepository = $appUserRepository;
    }

    public function getStatistics(): array
    {
        return [
            'articleCount' => $this->articleRepository->findNbResults(),
            'productCount' => $this->productRepository->findNbResults(),
            'personCount' => $this->personRepository->findNbResults(),
            'userCount' => $this->appUserRepository->findNbResults(),
            'ratingCount' => $this->productReviewRepository->findNbResults(),
        ];
    }

    /**
     * Same figures as getStatistics(), restricted to what happened during the period, like the Sylius dashboard.
     *
     * @return array{articleCount: int, productCount: int, userCount: int, ratingCount: int}
     */
    public function getStatisticsForPeriod(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return [
            'articleCount' => $this->countInPeriod($this->articleRepository, 'publishStartDate', $startDate, $endDate, ['status' => Article::STATUS_PUBLISHED]),
            'productCount' => $this->countInPeriod($this->productRepository, 'createdAt', $startDate, $endDate),
            'userCount' => $this->countInPeriod($this->appUserRepository, 'createdAt', $startDate, $endDate),
            'ratingCount' => $this->countInPeriod($this->productReviewRepository, 'createdAt', $startDate, $endDate),
        ];
    }

    /**
     * @param array<string, mixed> $criteria
     */
    private function countInPeriod(RepositoryInterface $repository, string $dateField, \DateTimeInterface $startDate, \DateTimeInterface $endDate, array $criteria = []): int
    {
        \assert($repository instanceof EntityRepository);

        $queryBuilder = $repository->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where(sprintf('o.%s >= :startDate', $dateField))
            ->andWhere(sprintf('o.%s < :endDate', $dateField))
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        foreach ($criteria as $field => $value) {
            $queryBuilder
                ->andWhere(sprintf('o.%1$s = :%1$s', $field))
                ->setParameter($field, $value);
        }

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
