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

use App\Entity\Product;
use Sylius\Bundle\GridBundle\Builder\Action\Action;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'sylius_backend_product',
    resourceClass: Product::class,
)]
final class ProductGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createQueryBuilderWithLocaleCode', [
                $this->locale,
            ])
            ->orderBy('createdAt', 'desc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('image', 'backend/product/grid/field/image.html.twig')
                    ->setPath('.')
                    ->setLabel('sylius.ui.image'),
                StringField::create('code')
                    ->setLabel('sylius.ui.code')
                    ->setSortable(true),
                StringField::create('name')
                    ->setLabel('sylius.ui.name'),
                TwigField::create('createdAt', 'backend/grid/field/date/short.html.twig')
                    ->setLabel('sylius.ui.creation_date')
                    ->setSortable(true),
                TwigField::create('status', 'backend/grid/field/state.html.twig')
                    ->setLabel('sylius.ui.status')
                    ->withOptions([
                        'vars' => [
                            'labels' => 'backend/product/label/state',
                        ],
                    ]),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'code',
                            'translation.name',
                        ],
                    ]),
                Filter::create('status', 'status')
                    ->setFormOptions([
                        'statuses' => [
                            'sylius.ui.new' => 'new',
                            'sylius.ui.pending_translation' => 'pending_translation',
                            'sylius.ui.pending_review' => 'pending_review',
                            'sylius.ui.pending_publication' => 'pending_publication',
                            'sylius.ui.published' => 'published',
                        ],
                    ]),
            )
            ->withMainActions(
                Action::create('create', 'create_product'),
            )
            ->withItemActions(
                UpdateAction::create(),
                DeleteAction::create(),
            )
            ->withSubItemActions(
                Action::create('manage_articles', 'manage_articles'),
                Action::create('manage_variants', 'manage_variants'),
            )
        ;
    }
}
