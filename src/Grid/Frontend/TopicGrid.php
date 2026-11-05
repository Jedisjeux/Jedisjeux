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

use App\Entity\Topic;
use Sylius\Bundle\GridBundle\Builder\Field\Field;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_topic',
    resourceClass: Topic::class,
)]
final class TopicGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createFrontendListQueryBuilder', [
                'localeCode' => $this->locale,
                'showPrivate' => 'expr:service(\'security.authorization_checker\').isGranted(\'ROLE_STAFF\')',
            ])
            ->orderBy('createdAt', 'desc')
            ->withFields(
                Field::create('createdAt', 'date')
                    ->setPath('lastPostCreatedAt')
                    ->setSortable(true, 'lastPostCreatedAt'),
                StringField::create('postCount')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('mainTaxon', 'frontend_taxon')
                    ->setFormOptions([
                        'label' => 'sylius.ui.category',
                        'taxon_code' => 'forum',
                    ]),
            )
        ;
    }
}
