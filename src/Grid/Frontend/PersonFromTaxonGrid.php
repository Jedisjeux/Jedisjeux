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

use App\Entity\Person;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_person_from_taxon',
    resourceClass: Person::class,
)]
final class PersonFromTaxonGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createFrontendListQueryBuilder', [
                'criteria' => 'expr:service(\'request_stack\').getCurrentRequest().get(\'criteria\', [])',
                'sorting' => 'expr:service(\'request_stack\').getCurrentRequest().get(\'sorting\', [])',
                'taxon' => 'expr:service(\'sylius.repository.taxon\').findOneBySlug($slug, \''.$this->locale.'\')',
            ])
            ->extends('app_frontend_person')
            ->orderBy('productCount', 'desc')
        ;
    }
}
