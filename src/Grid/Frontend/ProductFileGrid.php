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

use App\Entity\ProductFile;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_product_file',
    resourceClass: ProductFile::class,
)]
final class ProductFileGrid
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
