<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Review\Model\Review;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
#[ORM\Entity]
#[ORM\Table(name: 'jdj_article_review')]
#[AsResource(
    alias: 'sylius.article_review',
    section: 'backend',
    templatesDir: 'backend/crud',
    routePrefix: '/admin',
    vars: ['templates' => ['form' => 'backend/article_review/_form.html.twig']],
    operations: [
        new Index(
            path: 'articles/{articleId}/reviews/',
            grid: 'sylius_backend_article_review',
            vars: [
                'route' => ['parameters' => ['articleId' => '$articleId']],
                'icon' => 'cubes',
                'subheader' => 'sylius.ui.manage_article_reviews',
            ],
        ),
        new Create(
            path: 'articles/{articleId}/reviews/new',
            factoryMethod: 'createForSubjectWithReviewer',
            factoryArguments: [
                'subject' => "@=throw_not_found_on_null(sylius_repositories.get('app.repository.article').find(request.attributes.get('articleId')))",
                'reviewer' => '@=user.getCustomer()',
            ],
            redirectToRoute: 'sylius_backend_article_review_index',
            redirectArguments: ['articleId' => 'resource.reviewSubject.id'],
            vars: ['route' => ['parameters' => ['articleId' => '$articleId']]],
        ),
        new Update(
            path: 'articles/{articleId}/reviews/{id}/edit',
            repositoryMethod: 'findOneBy',
            repositoryArguments: [
                'criteria' => "@={'id': request.attributes.get('id'), 'reviewSubject': request.attributes.get('articleId')}",
            ],
            redirectToRoute: 'sylius_backend_article_review_index',
            redirectArguments: ['articleId' => 'resource.reviewSubject.id'],
            vars: [
                'route' => [
                    'parameters' => [
                        'id' => '$id',
                        'articleId' => '$articleId',
                    ],
                ],
                'index' => ['route' => ['parameters' => ['articleId' => '$articleId']]],
            ],
        ),
        new Delete(
            path: 'article-reviews/{id}',
            redirectToRoute: 'sylius_backend_article_review_index',
            redirectArguments: ['articleId' => 'resource.reviewSubject.id'],
        ),
    ],
)]
class ArticleReview extends Review
{
    /**
     * ProductReview constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->status = ReviewInterface::STATUS_ACCEPTED;
    }
}
