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

use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'sylius_frontend_product_from_person',
)]
final class ProductFromPersonGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setLimits([16])
            ->setRepositoryMethod('createListQueryBuilder', [
                'locale' => $this->locale,
                'onlyPublished' => true,
                'criteria' => 'expr:service(\'request_stack\').getCurrentRequest().get(\'criteria\', [])',
                'taxon' => null,
                'person' => 'expr:service(\'app.repository.person\').findOneBySlug($slug)',
            ])
            ->extends('sylius_frontend_product')
            ->orderBy('createdAt', 'desc')
        ;
    }
}
