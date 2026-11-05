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

namespace App\Grid\Backend;

use App\Entity\NotFound;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_backend_not_found_page',
    resourceClass: NotFound::class,
)]
final class NotFoundPageGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->orderBy('timestamp', 'desc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('timestamp', 'backend/grid/field/date/medium.html.twig')
                    ->setLabel('sylius.ui.creation_date')
                    ->setSortable(true),
                StringField::create('path')
                    ->setLabel('app.ui.path')
                    ->setSortable(true),
                TwigField::create('referer', 'backend/not_found_page/grid/field/referer.html.twig')
                    ->setLabel('app.ui.referer')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'path',
                            'referer',
                        ],
                    ]),
                Filter::create('timestamp', 'date')
                    ->setLabel('sylius.ui.creation_date'),
            )
        ;
    }
}
