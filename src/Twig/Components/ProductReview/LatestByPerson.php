<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Twig\Components\ProductReview;

use App\Entity\ProductReview;
use App\Repository\ProductReviewRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * Latest reviews of the games of a person.
 */
#[AsTwigComponent(name: 'ProductReview:LatestByPerson', template: 'frontend/product_review/_list2.html.twig')]
final class LatestByPerson
{
    public int $personId;

    public int $count = 4;

    public function __construct(
        #[Autowire(service: 'sylius.repository.product_review')]
        private readonly ProductReviewRepository $productReviewRepository,
    ) {
    }

    /**
     * @return ProductReview[]
     */
    #[ExposeInTemplate('product_reviews')]
    public function getProductReviews(): array
    {
        return $this->productReviewRepository->findLatestByPersonId($this->personId, $this->count);
    }
}
