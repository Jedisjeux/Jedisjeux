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

use App\Entity\GamePlay;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_backend_game_play',
    resourceClass: GamePlay::class,
)]
final class GamePlayGrid
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
            ->orderBy('createdAt', 'desc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('avatar', 'backend/customer/grid/field/avatar.html.twig')
                    ->setPath('author')
                    ->setLabel('app.ui.avatar'),
                StringField::create('name')
                    ->setPath('author')
                    ->setLabel('sylius.ui.username'),
                StringField::create('product')
                    ->setPath('product')
                    ->setLabel('sylius.ui.product'),
                TwigField::create('createdAt', 'backend/grid/field/date/medium.html.twig')
                    ->setLabel('sylius.ui.creation_date')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'author.email',
                            'productTranslation.name',
                        ],
                    ]),
            )
            ->withItemActions(
                UpdateAction::create(),
                DeleteAction::create(),
            )
        ;
    }
}
