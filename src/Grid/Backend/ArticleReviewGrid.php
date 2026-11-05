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

use App\Entity\ArticleReview;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'sylius_backend_article_review',
    resourceClass: ArticleReview::class,
)]
final class ArticleReviewGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createQueryBuilderByArticleId', [
                '$articleId',
            ])
            ->orderBy('createdAt', 'asc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('createdAt', 'backend/grid/field/date/medium.html.twig')
                    ->setLabel('sylius.ui.creation_date')
                    ->setSortable(true, 'createdAt'),
                StringField::create('author')
                    ->setLabel('sylius.ui.customer'),
                StringField::create('rating')
                    ->setLabel('sylius.ui.rating')
                    ->setSortable(true),
            )
            ->withMainActions(
                CreateAction::create()
                    ->setOptions([
                        'link' => [
                            'parameters' => [
                                'articleId' => '$articleId',
                            ],
                        ],
                    ]),
            )
            ->withItemActions(
                UpdateAction::create()
                    ->setOptions([
                        'link' => [
                            'parameters' => [
                                'id' => 'resource.id',
                                'articleId' => '$articleId',
                            ],
                        ],
                    ]),
                DeleteAction::create()
                    ->setOptions([
                        'link' => [
                            'parameters' => [
                                'id' => 'resource.id',
                                'articleId' => '$articleId',
                            ],
                        ],
                    ]),
            )
        ;
    }
}
