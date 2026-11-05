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

use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;
use Sylius\Component\Product\Model\ProductAssociationType;

#[AsGrid(
    name: 'sylius_backend_product_association_type',
    resourceClass: ProductAssociationType::class,
)]
final class ProductAssociationTypeGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->orderBy('code', 'asc')
            ->setLimits([10, 25, 50])
            ->withFields(
                StringField::create('code')
                    ->setLabel('sylius.ui.code')
                    ->setSortable(true),
                StringField::create('name')
                    ->setLabel('sylius.ui.name'),
            )
            ->withFilters(
                Filter::create('code', 'string')
                    ->setLabel('sylius.ui.code'),
                Filter::create('name', 'string')
                    ->setLabel('sylius.ui.name'),
            )
            ->withMainActions(
                CreateAction::create(),
            )
            ->withItemActions(
                UpdateAction::create(),
                DeleteAction::create(),
            )
        ;
    }
}
