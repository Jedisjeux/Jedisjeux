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

use App\Entity\Topic;
use Sylius\Bundle\GridBundle\Builder\Action\Action;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_backend_topic',
    resourceClass: Topic::class,
)]
final class TopicGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->orderBy('createdAt', 'desc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('avatar', 'backend/customer/grid/field/avatar.html.twig')
                    ->setPath('author')
                    ->setLabel('app.ui.avatar'),
                StringField::create('author')
                    ->setLabel('sylius.ui.username'),
                StringField::create('title')
                    ->setLabel('sylius.ui.title')
                    ->setSortable(true, 'title'),
                TwigField::create('createdAt', 'backend/grid/field/date/medium.html.twig')
                    ->setLabel('sylius.ui.creation_date')
                    ->setSortable(true, 'createdAt'),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'title',
                        ],
                    ]),
            )
            ->withItemActions(
                Action::create('posts', 'list_posts'),
                UpdateAction::create(),
                DeleteAction::create(),
            )
            ->withBulkActions(
                DeleteAction::create(),
            )
        ;
    }
}
