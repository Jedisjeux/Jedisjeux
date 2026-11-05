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

use App\Entity\ProductReview;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\Field;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'sylius_backend_product_review',
    resourceClass: ProductReview::class,
)]
final class ProductReviewGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->orderBy('date', 'desc')
            ->setLimits([10, 25, 50])
            ->withFields(
                Field::create('date', 'datetime')
                    ->setPath('createdAt')
                    ->setLabel('sylius.ui.date')
                    ->setSortable(true, 'createdAt')
                    ->withOptions([
                        'format' => 'd-m-Y H:i:s',
                    ]),
                StringField::create('title')
                    ->setLabel('sylius.ui.title')
                    ->setSortable(true),
                StringField::create('rating')
                    ->setLabel('sylius.ui.rating')
                    ->setSortable(true),
                StringField::create('reviewSubject')
                    ->setLabel('sylius.ui.product'),
                StringField::create('author')
                    ->setLabel('sylius.ui.customer'),
            )
            ->withFilters(
                Filter::create('title', 'string')
                    ->setLabel('sylius.ui.title'),
            )
            ->withItemActions(
                UpdateAction::create(),
                DeleteAction::create(),
            )
        ;
    }
}
