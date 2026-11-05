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
use Sylius\Bundle\GridBundle\Builder\Action\Action;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_backend_game_award',
    resourceClass: GameAward::class,
)]
final class GameAwardGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->orderBy('name', 'asc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('image', 'backend/game_award/grid/field/image.html.twig')
                    ->setPath('.')
                    ->setLabel('sylius.ui.image'),
                StringField::create('name')
                    ->setLabel('sylius.ui.name')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'name',
                        ],
                    ]),
            )
            ->withMainActions(
                CreateAction::create(),
            )
            ->withItemActions(
                UpdateAction::create(),
                DeleteAction::create(),
                Action::create('yearAwards', 'links')
                    ->setLabel('app.ui.manage_year_awards')
                    ->setOptions([
                        'links' => [
                            'index' => [
                                'label' => 'app.ui.year_awards',
                                'route' => 'app_backend_year_award_index',
                                'parameters' => [
                                    'criteria' => [
                                        'award' => 'resource.id',
                                    ],
                                ],
                            ],
                        ],
                    ]),
            )
        ;
    }
}
