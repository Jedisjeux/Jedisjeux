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

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Latest published games as a list, like on the page of a person.
 */
#[AsTwigComponent(name: 'Product:LatestList', template: 'frontend/product/_list.html.twig')]
final class LatestList extends Latest
{
}
