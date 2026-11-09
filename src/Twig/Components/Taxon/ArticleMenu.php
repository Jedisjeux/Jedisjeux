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
 * Article categories in the main menu.
 */
#[AsTwigComponent(name: 'Taxon:ArticleMenu', template: 'frontend/taxon/_listForArticles.html.twig')]
final class ArticleMenu extends Children
{
}
