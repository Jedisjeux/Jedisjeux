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

use App\Entity\Article;
use Sylius\Bundle\GridBundle\Builder\Action\Action;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_backend_article',
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
            ->setRepositoryMethod('createListQueryBuilder', [
                $this->locale,
                '$productId',
            ])
            ->orderBy('createdAt', 'desc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('image', 'backend/article/grid/field/image.html.twig')
                    ->setPath('.')
                    ->setLabel('sylius.ui.image'),
                StringField::create('title')
                    ->setLabel('sylius.ui.title')
                    ->setSortable(true, 'title'),
                StringField::create('category')
                    ->setPath('mainTaxon')
                    ->setLabel('sylius.ui.category'),
                TwigField::create('status', 'backend/grid/field/state.html.twig')
                    ->setLabel('sylius.ui.status')
                    ->withOptions([
                        'vars' => [
                            'labels' => 'backend/article/label/state',
                        ],
                    ]),
                TwigField::create('createdAt', 'backend/grid/field/date/medium.html.twig')
                    ->setLabel('app.ui.created_at')
                    ->setSortable(true, 'createdAt'),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions([
                        'fields' => [
                            'title',
                        ],
                    ]),
                Filter::create('status', 'status')
                    ->setFormOptions([
                        'label' => 'sylius.ui.status',
                        'statuses' => [
                            'sylius.ui.new' => 'new',
                            'sylius.ui.pending_review' => 'pending_review',
                            'sylius.ui.pending_publication' => 'pending_publication',
                            'sylius.ui.published' => 'published',
                        ],
                    ]),
                Filter::create('createdAt', 'date')
                    ->setLabel('app.ui.created_at')
                    ->setOptions([
                        'inclusive_to' => true,
                    ]),
                Filter::create('publishStartDate', 'date')
                    ->setLabel('app.ui.published_at')
                    ->setOptions([
                        'inclusive_to' => true,
                    ]),
                Filter::create('mainTaxon', 'taxon')
                    ->setFormOptions([
                        'label' => 'sylius.ui.category',
                        'taxon_code' => 'articles',
                    ]),
            )
            ->withMainActions(
                CreateAction::create(),
            )
            ->withItemActions(
                UpdateAction::create(),
                DeleteAction::create(),
            )
            ->withSubItemActions(
                Action::create('manage_reviews', 'manage_article_reviews'),
            )
        ;
    }
}
