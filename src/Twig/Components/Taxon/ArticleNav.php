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

namespace App\Twig\Components\Taxon;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Article categories in the sidebar of an article.
 */
#[AsTwigComponent(name: 'Taxon:ArticleNav', template: 'frontend/taxon/_navListForArticles.html.twig')]
final class ArticleNav extends Children
{
}
