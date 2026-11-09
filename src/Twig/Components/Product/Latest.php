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

/**
 * Latest published games, optionally restricted to the games of a person.
 */
#[AsTwigComponent(name: 'Product:Latest', template: 'frontend/product/grid/_fourColumns.html.twig')]
class Latest
{
    public int $count = 4;

    public ?int $personId = null;

    /** Sorted on the creation date by default, or on the release date. */
    public string $sortedBy = 'createdAt';

    public function __construct(
        #[Autowire(service: 'sylius.repository.product')]
        private readonly ProductRepository $productRepository,
        #[Autowire(service: 'sylius.context.locale')]
        private readonly LocaleContextInterface $localeContext,
    ) {
    }

    /**
     * @return Product[]
     */
    #[ExposeInTemplate]
    public function getProducts(): array
    {
        $criteria = null !== $this->personId ? ['person' => $this->personId] : [];

        return $this->productRepository
            ->createListQueryBuilder($this->localeContext->getLocaleCode(), true, $criteria)
            ->addOrderBy('releasedAt' === $this->sortedBy ? 'variant.releasedAt' : 'o.createdAt', 'DESC')
            ->setMaxResults($this->count)
            ->getQuery()
            ->getResult()
        ;
    }
}
