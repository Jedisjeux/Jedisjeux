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

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Latest published articles as a compact list, for sidebars.
 */
#[AsTwigComponent(name: 'Article:LatestList', template: 'frontend/article/_simpleList.html.twig')]
final class LatestList extends Latest
{
}
