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

namespace App\Twig\Components\ProductFile;

use App\Entity\ProductFile;
use App\Repository\ProductFileRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'ProductFile:LatestForProduct', template: 'frontend/product_file/_list.html.twig')]
final class LatestForProduct
{
    public int $productId;

    public int $count = 3;

    public function __construct(
        #[Autowire(service: 'app.repository.product_file')]
        private readonly ProductFileRepository $productFileRepository,
    ) {
    }

    /**
     * @return ProductFile[]
     */
    #[ExposeInTemplate('product_files')]
    public function getProductFiles(): array
    {
        return $this->productFileRepository->findLatestByProductId($this->productId, $this->count);
    }
}
