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

use App\Entity\FestivalListItem;
use Sylius\Bundle\GridBundle\Builder\Field\Field;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_festival_list_item',
    resourceClass: FestivalListItem::class,
)]
final class FestivalListItemGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createListQueryBuilderByList', [
                'expr:notFoundOnNull(service(\'app.repository.festival_list\').findOneBySlug($festivalListSlug))',
                $this->locale,
            ])
            ->orderBy('createdAt', 'desc')
            ->withFields(
                Field::create('createdAt', 'twig')
                    ->setLabel('sylius.ui.created_at')
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
