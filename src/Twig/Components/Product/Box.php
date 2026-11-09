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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * Card of the game a page is about, found by its slug.
 */
#[AsTwigComponent(name: 'Product:Box', template: 'frontend/product/_box.html.twig')]
final class Box
{
    public string $slug;

    public function __construct(
        #[Autowire(service: 'sylius.repository.product')]
        private readonly ProductRepository $productRepository,
        #[Autowire(service: 'sylius.context.locale')]
        private readonly LocaleContextInterface $localeContext,
    ) {
    }

    #[ExposeInTemplate]
    public function getProduct(): Product
    {
        $product = $this->productRepository->findOneBySlug($this->localeContext->getLocaleCode(), $this->slug);

        if (!$product instanceof Product) {
            throw new NotFoundHttpException(sprintf('Product "%s" does not exist.', $this->slug));
        }

        return $product;
    }
}
