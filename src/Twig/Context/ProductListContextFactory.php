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

namespace App\Twig\Context;

use App\Entity\ProductList;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Adds the list of games of the URL slug to the template listing its games.
 */
final class ProductListContextFactory implements ContextFactoryInterface
{
    /**
     * @param RepositoryInterface<ProductList> $productListRepository
     */
    public function __construct(
        private readonly ContextFactoryInterface $decorated,
        #[Autowire(service: 'app.repository.product_list')]
        private readonly RepositoryInterface $productListRepository,
    ) {
    }

    public function create(mixed $data, Operation $operation, Context $context): array
    {
        $slug = $context->get(RequestOption::class)?->request()?->attributes->get('productListSlug');
        $productList = $this->productListRepository->findOneBy(['slug' => $slug]);

        if (!$productList instanceof ProductList) {
            throw new NotFoundHttpException(sprintf('Product list "%s" does not exist.', (string) $slug));
        }

        return array_merge($this->decorated->create($data, $operation, $context), ['product_list' => $productList]);
    }
}
