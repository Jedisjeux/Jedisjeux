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

use App\Entity\ProductFile;
use Sylius\Bundle\GridBundle\Builder\Action\Action;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_backend_product_file',
    resourceClass: ProductFile::class,
)]
final class ProductFileGrid
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
                TwigField::create('image', 'backend/product_file/grid/field/image.html.twig')
                    ->setPath('.')
                    ->setLabel('sylius.ui.image'),
                StringField::create('product')
                    ->setPath('product')
                    ->setLabel('app.ui.game'),
                StringField::create('title')
                    ->setLabel('sylius.ui.title'),
                TwigField::create('status', 'backend/grid/field/state.html.twig')
                    ->setLabel('sylius.ui.status')
                    ->setSortable(true)
                    ->withOptions([
                        'vars' => [
                            'labels' => 'backend/product_file/label/status',
                        ],
                    ]),
                TwigField::create('createdAt', 'backend/grid/field/date/medium.html.twig')
                    ->setLabel('sylius.ui.creation_date')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'productTranslation.name',
                            'title',
                        ],
                    ]),
            )
            ->withItemActions(
                UpdateAction::create(),
                Action::create('accept', 'apply_transition')
                    ->setLabel('sylius.ui.accept')
                    ->setIcon('tabler:check')
                    ->setOptions([
                        'link' => [
                            'route' => 'app_backend_product_file_accept',
                            'parameters' => [
                                'id' => 'resource.id',
                            ],
                        ],
                        'class' => 'btn-sm btn-outline-success',
                        'transition' => 'accept',
                        'graph' => 'app_product_file',
                    ]),
                Action::create('reject', 'apply_transition')
                    ->setLabel('sylius.ui.reject')
                    ->setIcon('tabler:x')
                    ->setOptions([
                        'link' => [
                            'route' => 'app_backend_product_file_reject',
                            'parameters' => [
                                'id' => 'resource.id',
                            ],
                        ],
                        'class' => 'btn-sm btn-outline-warning',
                        'transition' => 'reject',
                        'graph' => 'app_product_file',
                    ]),
                DeleteAction::create(),
            )
        ;
    }
}
