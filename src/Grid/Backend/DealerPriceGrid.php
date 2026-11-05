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

use App\Entity\Dealer;
use App\Entity\DealerPrice;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_backend_dealer_price',
    resourceClass: DealerPrice::class,
)]
final class DealerPriceGrid
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
            ->orderBy('url', 'desc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('image', 'backend/dealer_price/grid/field/image.html.twig')
                    ->setPath('product')
                    ->setLabel('sylius.ui.image'),
                TwigField::create('product', 'backend/dealer_price/grid/field/product.html.twig')
                    ->setLabel('sylius.ui.product'),
                StringField::create('name')
                    ->setLabel('sylius.ui.name')
                    ->setSortable(true, 'name'),
                TwigField::create('url', 'backend/dealer_price/grid/field/url.html.twig')
                    ->setLabel('app.ui.path')
                    ->setSortable(true),
                StringField::create('dealer')
                    ->setLabel('app.ui.dealer'),
                TwigField::create('status', 'backend/grid/field/state.html.twig')
                    ->setLabel('sylius.ui.status')
                    ->withOptions([
                        'vars' => [
                            'labels' => 'backend/dealer_price/label/state',
                        ],
                    ]),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'name',
                            'barcode',
                        ],
                    ]),
                Filter::create('dealer', 'entity')
                    ->setLabel('app.ui.dealer')
                    ->setFormOptions([
                        'class' => Dealer::class,
                    ]),
                Filter::create('status', 'status')
                    ->setFormOptions([
                        'label' => 'sylius.ui.status',
                        'statuses' => [
                            'sylius.ui.available' => 'available',
                            'sylius.ui.pre_order' => 'pre_order',
                            'sylius.ui.out_of_stock' => 'out_of_stock',
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
