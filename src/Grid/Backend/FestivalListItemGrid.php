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

use App\Entity\FestivalListItem;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\Field;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_backend_festival_list_item',
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
                'expr:notFoundOnNull(service(\'app.repository.festival_list\').find($festivalListId))',
                $this->locale,
            ])
            ->orderBy('createdAt', 'desc')
            ->withFields(
                TwigField::create('image', 'backend/festival_list_item/grid/field/image.html.twig')
                    ->setPath('.')
                    ->setLabel('sylius.ui.image'),
                StringField::create('product')
                    ->setLabel('sylius.ui.product')
                    ->setSortable(true),
                Field::create('releasedAt', 'datetime')
                    ->setPath('product.firstVariant.releasedAt')
                    ->setLabel('app.ui.release_date')
                    ->setSortable(true)
                    ->withOptions([
                        'format' => 'd M Y',
                    ]),
                Field::create('createdAt', 'datetime')
                    ->setLabel('sylius.ui.created_at')
                    ->setSortable(true)
                    ->withOptions([
                        'format' => 'd M Y',
                    ]),
            )
            ->withMainActions(
                CreateAction::create()
                    ->setOptions([
                        'link' => [
                            'parameters' => [
                                'festivalListId' => '$festivalListId',
                            ],
                        ],
                    ]),
            )
            ->withItemActions(
                UpdateAction::create()
                    ->setOptions([
                        'link' => [
                            'parameters' => [
                                'id' => 'resource.id',
                                'festivalListId' => '$festivalListId',
                            ],
                        ],
                    ]),
                DeleteAction::create()
                    ->setOptions([
                        'link' => [
                            'parameters' => [
                                'id' => 'resource.id',
                                'festivalListId' => '$festivalListId',
                            ],
                        ],
                    ]),
            )
        ;
    }
}
