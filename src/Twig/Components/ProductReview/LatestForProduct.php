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

#[AsTwigComponent(name: 'ProductReview:LatestForProduct', template: 'frontend/product_review/_list.html.twig')]
final class LatestForProduct
{
    public int $productId;

    public int $count = 3;

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
        return $this->productReviewRepository->findLatestByProductId($this->productId, $this->count);
    }
}
