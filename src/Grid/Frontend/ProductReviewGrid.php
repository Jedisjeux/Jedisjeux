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

namespace App\Grid\Frontend;

use App\Entity\ProductReview;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'sylius_frontend_product_review',
    resourceClass: ProductReview::class,
)]
final class ProductReviewGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createListForProductSlugQueryBuilder', [
                'locale' => 'expr:service(\'sylius.context.locale\').getLocaleCode()',
                'productSlug' => '$slug',
            ])
            ->orderBy('createdAt', 'desc')
            ->withFields(
                StringField::create('createdAt')
                    ->setSortable(true),
            )
        ;
    }
}
