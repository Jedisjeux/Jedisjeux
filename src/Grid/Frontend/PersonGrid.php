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
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_person',
    resourceClass: Person::class,
)]
final class PersonGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createFrontendListQueryBuilder', [
                'criteria' => 'expr:service(\'request_stack\').getCurrentRequest().get(\'criteria\', [])',
                'sorting' => 'expr:service(\'request_stack\').getCurrentRequest().get(\'sorting\', [])',
            ])
            ->orderBy('productCount', 'desc')
            ->withFields(
                StringField::create('productCount')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('role', 'frontend_person_role')
                    ->setFormOptions([
                        'placeholder' => 'sylius.ui.all',
                    ]),
                Filter::create('zone', 'frontend_taxon')
                    ->setOptions([
                        'field' => 'taxon',
                    ])
                    ->setFormOptions([
                        'label' => 'sylius.ui.category',
                        'placeholder' => 'app.ui.all_female',
                        'taxon_code' => 'zones',
                    ]),
            )
        ;
    }
}
