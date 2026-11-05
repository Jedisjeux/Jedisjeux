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

use App\Entity\ProductVariant;
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
    name: 'sylius_backend_product_variant',
    resourceClass: ProductVariant::class,
)]
final class ProductVariantGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createQueryBuilderByProductId', [
                $this->locale,
                '$productId',
            ])
            ->orderBy('position', 'asc')
            ->setLimits([10, 25, 50])
            ->withFields(
                StringField::create('name')
                    ->setLabel('sylius.ui.name'),
                StringField::create('code')
                    ->setLabel('sylius.ui.code'),
                TwigField::create('position', 'backend/product_variant/grid/field/position.html.twig')
                    ->setPath('.')
                    ->setLabel('sylius.ui.position')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('code', 'string')
                    ->setLabel('sylius.ui.code'),
                Filter::create('name', 'string')
                    ->setLabel('sylius.ui.name')
                    ->setOptions([
                        'fields' => [
                            'name',
                        ],
                    ]),
            )
            ->withMainActions(
                Action::create('update_positions', 'update_product_variant_positions'),
                CreateAction::create()
                    ->setOptions([
                        'link' => [
                            'parameters' => [
                                'productId' => '$productId',
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
                                'productId' => '$productId',
                            ],
                        ],
                    ]),
                DeleteAction::create()
                    ->setOptions([
                        'link' => [
                            'parameters' => [
                                'id' => 'resource.id',
                                'productId' => '$productId',
                            ],
                        ],
                    ]),
            )
        ;
    }
}
