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

namespace App\Twig\Components\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Webmozart\Assert\Assert;

/**
 * Published games released in a period, around the current month.
 */
#[AsTwigComponent(name: 'Product:Releases', template: 'frontend/product/releases/_list.html.twig')]
final class Releases
{
    public const THIS_MONTH = 'this_month';
    public const PREVIOUS_MONTHS = 'previous_months';
    public const NEXT_MONTHS = 'next_months';

    /**
     * Criteria of the catalog for each period, also used by the links to see all the releases.
     */
    public const CRITERIA = [
        self::THIS_MONTH => ['releasedAtFrom' => 'first_day_of_the_month', 'releasedAtTo' => 'last_day_of_the_month'],
        self::PREVIOUS_MONTHS => ['releasedAtTo' => 'last_day_of_previous_month'],
        self::NEXT_MONTHS => ['releasedAtFrom' => 'first_day_of_next_month'],
    ];

    public string $period = self::THIS_MONTH;

    public int $count = 4;

    public function __construct(
        #[Autowire(service: 'sylius.repository.product')]
        private readonly ProductRepository $productRepository,
        #[Autowire(service: 'sylius.context.locale')]
        private readonly LocaleContextInterface $localeContext,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    #[PreMount]
    public function validatePeriod(array $data): array
    {
        Assert::keyExists(self::CRITERIA, $data['period'] ?? self::THIS_MONTH);

        return $data;
    }

    /**
     * The upcoming releases come from the nearest, the others from the latest.
     */
    public static function getSortingDirection(string $period): string
    {
        return self::NEXT_MONTHS === $period ? 'asc' : 'desc';
    }

    /**
     * @return Product[]
     */
    #[ExposeInTemplate]
    public function getProducts(): array
    {
        return $this->productRepository
            ->createListQueryBuilder($this->localeContext->getLocaleCode(), true, self::CRITERIA[$this->period])
            ->andWhere('variant.releasedAt IS NOT NULL')
            ->addOrderBy('variant.releasedAt', self::getSortingDirection($this->period))
            ->setMaxResults($this->count)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Parameters of the catalog listing all the releases of the period.
     *
     * @return array{criteria: array<string, string>, sorting: array{releasedAt: string}}
     */
    #[ExposeInTemplate]
    public function getCatalogParameters(): array
    {
        return [
            'criteria' => self::CRITERIA[$this->period],
            'sorting' => ['releasedAt' => self::getSortingDirection($this->period)],
        ];
    }
}
