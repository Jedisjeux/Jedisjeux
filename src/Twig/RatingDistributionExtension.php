<?php

declare(strict_types=1);

namespace App\Twig;

use App\Repository\ProductReviewRepository;
use Sylius\Component\Product\Model\ProductInterface;

final class RatingDistributionExtension extends \Twig_Extension
{
    public function __construct(private readonly ProductReviewRepository $productReviewRepository)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_Function('product_rating_distribution', [$this, 'getDistribution']),
        ];
    }

    /**
     * Groups ratings out of 10 into five bands (9–10, 7–8, 5–6, 3–4, 1–2), best first.
     *
     * @return array<int, array{label: string, count: int, percent: int}>
     */
    public function getDistribution(ProductInterface $product): array
    {
        $counts = $this->productReviewRepository->countRatingsByProductId($product->getId());
        $total = array_sum($counts);
        $bands = [];

        for ($high = 10; $high >= 2; $high -= 2) {
            $count = ($counts[$high] ?? 0) + ($counts[$high - 1] ?? 0);
            $bands[] = [
                'label' => sprintf('%d–%d', $high - 1, $high),
                'count' => $count,
                'percent' => $total > 0 ? (int) round($count * 100 / $total) : 0,
            ];
        }

        return $bands;
    }
}
