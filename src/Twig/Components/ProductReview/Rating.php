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
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * Rating stars of a game, filled with the rating of the current customer.
 */
#[AsTwigComponent(name: 'ProductReview:Rating', template: 'frontend/product_review/rating/_show.html.twig')]
final class Rating
{
    public int $productId;

    public function __construct(
        #[Autowire(service: 'sylius.repository.product_review')]
        private readonly ProductReviewRepository $productReviewRepository,
        private readonly CustomerContextInterface $customerContext,
    ) {
    }

    #[ExposeInTemplate('product_review')]
    public function getProductReview(): ?ProductReview
    {
        $customer = $this->customerContext->getCustomer();

        if (null === $customer) {
            return null;
        }

        return $this->productReviewRepository->findOneBy([
            'reviewSubject' => $this->productId,
            'author' => $customer,
        ]);
    }
}
