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

use App\Entity\Product;
use Sylius\Bundle\GridBundle\Builder\Field\Field;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'sylius_frontend_product',
    resourceClass: Product::class,
)]
final class ProductGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createListQueryBuilder', [
                'locale' => $this->locale,
                'onlyPublished' => true,
                'criteria' => 'expr:service(\'request_stack\').getCurrentRequest().get(\'criteria\', [])',
            ])
            ->orderBy('createdAt', 'desc')
            ->withFields(
                StringField::create('createdAt')
                    ->setSortable(true),
                Field::create('averageRating', 'number')
                    ->setSortable(true),
                StringField::create('releasedAt')
                    ->setPath('variant.releasedAt')
                    ->setSortable(true, 'variant.releasedAt'),
                Field::create('commentedReviewCount', 'number')
                    ->setSortable(true),
                Field::create('viewCount', 'number')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'translation.name',
                        ],
                    ])
                    ->setFormOptions([
                        'type' => 'contains',
                    ]),
                Filter::create('playerCount', 'frontend_between_two_fields')
                    ->setOptions([
                        'fields' => [
                            'first' => 'minPlayerCount',
                            'second' => 'maxPlayerCount',
                        ],
                    ])
                    ->setFormOptions([
                        'label' => 'app.ui.player_count',
                        'placeholder' => 'app.ui.player_count',
                    ]),
                Filter::create('minAge', 'frontend_less_than_or_equal')
                    ->setFormOptions([
                        'label' => 'app.ui.age',
                        'placeholder' => 'app.ui.age_in_year',
                    ]),
                Filter::create('minDuration', 'frontend_greater_than_or_equal')
                    ->setFormOptions([
                        'label' => 'app.ui.min_duration',
                        'placeholder' => 'app.ui.duration_in_minute',
                    ]),
                Filter::create('maxDuration', 'frontend_less_than_or_equal')
                    ->setOptions([
                        'field' => 'o.minDuration',
                    ])
                    ->setFormOptions([
                        'label' => 'app.ui.max_duration',
                        'placeholder' => 'app.ui.duration_in_minute',
                    ]),
                Filter::create('reviewCount', 'frontend_greater_than_or_equal')
                    ->setFormOptions([
                        'label' => 'app.ui.min_rating_count',
                        'placeholder' => 'app.ui.min_rating_count',
                    ]),
                Filter::create('averageRatingMin', 'frontend_greater_than_or_equal')
                    ->setOptions([
                        'field' => 'o.averageRating',
                    ])
                    ->setFormOptions([
                        'label' => 'app.ui.min_average_rating',
                        'placeholder' => 'app.ui.min_average_rating',
                    ]),
            )
        ;
    }
}
