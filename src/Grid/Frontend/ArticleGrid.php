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

use App\Entity\Article;
use Sylius\Bundle\GridBundle\Builder\Field\Field;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_article',
    resourceClass: Article::class,
)]
final class ArticleGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setLimits([12])
            ->setRepositoryMethod('createFrontendListQueryBuilder', [
                'locale' => $this->locale,
                'taxonSlug' => '$slug',
                'productSlug' => '$productSlug',
                'criteria' => 'expr:service(\'request_stack\').getCurrentRequest().get(\'criteria\', [])',
            ])
            ->orderBy('publishStartDate', 'desc')
            ->withFields(
                StringField::create('title')
                    ->setLabel('sylius.ui.title')
                    ->setSortable(true),
                Field::create('viewCount', 'int')
                    ->setSortable(true),
                Field::create('topic.postCount', 'int')
                    ->setPath('topic.postCount'),
                Field::create('publishStartDate', 'date')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('mainTaxon', 'frontend_taxon')
                    ->setFormOptions([
                        'label' => 'sylius.ui.category',
                        'taxon_code' => 'articles',
                    ]),
            )
        ;
    }
}
