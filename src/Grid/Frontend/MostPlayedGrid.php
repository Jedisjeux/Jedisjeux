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

use App\Entity\Product;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'sylius_frontend_most_played',
    resourceClass: Product::class,
)]
final class MostPlayedGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createListOfMostPlayedQueryBuilder', [
                'locale' => $this->locale,
                'author' => 'expr:service(\'sylius.context.customer\').getCustomer()',
            ])
            ->withFields(
                StringField::create('createdAt')
                    ->setSortable(true),
                StringField::create('averageRating')
                    ->setSortable(true),
                StringField::create('variant.releasedAt')
                    ->setPath('variant.releasedAt')
                    ->setSortable(true),
            )
        ;
    }
}
