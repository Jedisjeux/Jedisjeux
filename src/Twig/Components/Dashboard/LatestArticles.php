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

namespace App\Twig\Components\Dashboard;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * Latest created articles, whatever their status, for the admin dashboard.
 */
#[AsTwigComponent(name: 'Dashboard:LatestArticles', template: 'backend/dashboard/_articles.html.twig')]
final class LatestArticles
{
    public int $count = 5;

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
        return $this->articleRepository->findLatest($this->count);
    }
}
