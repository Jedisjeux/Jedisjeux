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

use App\Entity\GameAward;
use App\Entity\YearAward;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_backend_year_award',
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
            ->setRepositoryMethod('createListQueryBuilder', [
                $this->locale,
            ])
            ->orderBy('year', 'desc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('image', 'backend/product/grid/field/image.html.twig')
                    ->setPath('product')
                    ->setLabel('sylius.ui.image'),
                StringField::create('name')
                    ->setLabel('sylius.ui.name')
                    ->setSortable(true),
                StringField::create('year')
                    ->setLabel('app.ui.year')
                    ->setSortable(true),
                StringField::create('product')
                    ->setLabel('app.ui.game'),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'award.name',
                        ],
                    ]),
                Filter::create('award', 'entity')
                    ->setLabel('app.ui.game_award')
                    ->setFormOptions([
                        'class' => GameAward::class,
                        'choice_label' => 'name',
                    ]),
            )
        ;
    }
}
