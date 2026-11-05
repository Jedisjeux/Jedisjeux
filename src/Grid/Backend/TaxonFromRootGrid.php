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

use App\Entity\Taxon;
use Sylius\Bundle\GridBundle\Builder\Action\Action;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'sylius_backend_taxon_from_root',
    resourceClass: Taxon::class,
)]
final class TaxonFromRootGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createChildrenListQueryBuilder', [
                [
                    $this->locale,
                ],
                'expr:service(\'sylius.repository.taxon\').findOneById($id)',
            ])
            ->setLimits([10, 25, 50])
            ->withFields(
                StringField::create('code')
                    ->setLabel('sylius.ui.code'),
                StringField::create('parent')
                    ->setLabel('sylius.ui.parent'),
                StringField::create('name')
                    ->setLabel('sylius.ui.name'),
                TwigField::create('public', 'backend/taxon/grid/field/public.html.twig')
                    ->setPath('.')
                    ->setLabel('app.ui.public'),
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
            )
            ->withMainActions(
                Action::create('create_for_parent', 'create_taxon_for_parent')
                    ->setOptions([
                        'link' => [
                            'parameters' => [
                                'id' => '$id',
                            ],
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
