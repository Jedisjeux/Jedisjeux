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

namespace App\Twig\Components\Article;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'Article:LatestForProduct', template: 'frontend/article/_list.html.twig')]
final class LatestForProduct
{
    public int $productId;

    public int $count = 3;

    public function __construct(
        #[Autowire(service: 'app.repository.article')]
        private readonly ArticleRepository $articleRepository,
    ) {
    }

    /**
     * @return Article[]
     */
    #[ExposeInTemplate]
    public function getArticles(): array
    {
        return $this->articleRepository->findLatestByProductId($this->productId, $this->count);
    }
}
