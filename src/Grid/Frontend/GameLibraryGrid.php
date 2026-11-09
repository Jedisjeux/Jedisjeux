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

namespace App\Grid\Frontend;

use App\Entity\ProductListItem;
use Sylius\Bundle\GridBundle\Builder\Field\Field;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_game_library',
    resourceClass: ProductListItem::class,
)]
final class GameLibraryGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setLimits([300])
            ->setRepositoryMethod('createQueryBuilderForGameLibrary', [
                'expr:service(\'sylius.context.customer\').getCustomer()',
                $this->locale,
            ])
            ->orderBy('box.height', 'desc')
            ->withFields(
                Field::create('createdAt', 'twig')
                    ->setSortable(true),
                Field::create('box.height', 'twig')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('playerCount', 'frontend_between_two_fields')
                    ->setOptions([
                        'fields' => [
                            'first' => 'product.minPlayerCount',
                            'second' => 'product.maxPlayerCount',
                        ],
                    ])
                    ->setFormOptions([
                        'label' => 'app.ui.player_count',
                        'placeholder' => 'app.ui.player_count',
                    ]),
                Filter::create('minAge', 'frontend_less_than_or_equal')
                    ->setOptions([
                        'field' => 'product.minAge',
                    ])
                    ->setFormOptions([
                        'label' => 'app.ui.age',
                        'placeholder' => 'app.ui.age_in_year',
                    ]),
                Filter::create('minDuration', 'frontend_greater_than_or_equal')
                    ->setOptions([
                        'field' => 'product.minDuration',
                    ])
                    ->setFormOptions([
                        'label' => 'app.ui.min_duration',
                        'placeholder' => 'app.ui.duration_in_minute',
                    ]),
                Filter::create('maxDuration', 'frontend_less_than_or_equal')
                    ->setOptions([
                        'field' => 'product.minDuration',
                    ])
                    ->setFormOptions([
                        'label' => 'app.ui.max_duration',
                        'placeholder' => 'app.ui.duration_in_minute',
                    ]),
            )
        ;
    }
}
