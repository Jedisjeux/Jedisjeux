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
 * Forum categories in the sidebar of the forum.
 */
#[AsTwigComponent(name: 'Taxon:TopicNav', template: 'frontend/taxon/_navListForTopics.html.twig')]
final class TopicNav extends Children
{
}
