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

use App\Repository\ProductReviewRepository;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * Link to write a review of a game, or to update the one of the current customer.
 */
#[AsTwigComponent(name: 'ProductReview:ReviewLink', template: 'frontend/product_review/_showCreateOrUpdateReviewLink.html.twig')]
final class ReviewLink
{
    public string $productSlug;

    public function __construct(
        #[Autowire(service: 'sylius.repository.product_review')]
        private readonly ProductReviewRepository $productReviewRepository,
        private readonly CustomerContextInterface $customerContext,
        #[Autowire(service: 'sylius.context.locale')]
        private readonly LocaleContextInterface $localeContext,
    ) {
    }

    #[ExposeInTemplate('product_review')]
    public function getProductReview(): ?ReviewInterface
    {
        $customer = $this->customerContext->getCustomer();

        if (null === $customer) {
            return null;
        }

        return $this->productReviewRepository->findOneByProductSlugAndAuthor(
            $this->localeContext->getLocaleCode(),
            $this->productSlug,
            $customer,
        );
    }
}
