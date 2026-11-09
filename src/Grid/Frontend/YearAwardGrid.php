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

use App\Entity\GameAward;
use App\Entity\YearAward;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_year_award',
    resourceClass: YearAward::class,
)]
final class YearAwardGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setLimits([12])
            ->setRepositoryMethod('createListQueryBuilder', [
                $this->locale,
                '$slug',
            ])
            ->orderBy('year', 'desc')
            ->withFields(
                StringField::create('name')
                    ->setSortable(true),
                StringField::create('year')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('award', 'frontend_entity')
                    ->setLabel('app.ui.game_award')
                    ->setFormOptions([
                        'class' => GameAward::class,
                        'choice_value' => 'slug',
                        'choice_label' => 'name',
                    ]),
            )
        ;
    }
}
