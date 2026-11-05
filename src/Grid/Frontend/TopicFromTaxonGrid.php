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
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_topic_from_taxon',
    resourceClass: Topic::class,
)]
final class TopicFromTaxonGrid
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
                'taxon' => 'expr:service(\'sylius.repository.taxon\').findOneBySlug($slug, \''.$this->locale.'\')',
            ])
            ->extends('app_frontend_topic')
        ;
    }
}
