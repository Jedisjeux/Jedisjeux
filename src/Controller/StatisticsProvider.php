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

use App\Repository\ArticleRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class StatisticsProvider
{
    /** @var ArticleRepository */
    private $articleRepository;

    /** @var ProductRepository */
    private $productRepository;

    /** @var RepositoryInterface */
    private $personRepository;

    /** @var UserRepository */
    private $appUserRepository;

    /** @var RepositoryInterface */
    private $productReviewRepository;

    public function __construct(RepositoryInterface $articleRepository, RepositoryInterface $productRepository, RepositoryInterface $personRepository, RepositoryInterface $appUserRepository, RepositoryInterface $productReviewRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->productRepository = $productRepository;
        $this->personRepository = $personRepository;
        $this->appUserRepository = $appUserRepository;
        $this->productReviewRepository = $productReviewRepository;
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
}
