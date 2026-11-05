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

use App\Entity\FestivalList;
use Sylius\Bundle\GridBundle\Builder\Action\Action;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\Field;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_backend_festival_list',
    resourceClass: FestivalList::class,
)]
final class FestivalListGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->orderBy('startAt', 'desc')
            ->setLimits([10, 25, 50])
            ->withFields(
                StringField::create('name')
                    ->setLabel('sylius.ui.name')
                    ->setSortable(true),
                Field::create('startAt', 'datetime')
                    ->setLabel('sylius.ui.start_date')
                    ->setSortable(true)
                    ->withOptions([
                        'format' => 'd M Y',
                    ]),
                Field::create('endAt', 'datetime')
                    ->setLabel('sylius.ui.end_date')
                    ->setSortable(true)
                    ->withOptions([
                        'format' => 'd M Y',
                    ]),
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
            )
            ->withSubItemActions(
                Action::create('items', 'manage_festival_list_items'),
            )
        ;
    }
}
