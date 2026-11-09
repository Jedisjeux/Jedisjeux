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
#[ORM\Table(name: 'sylius_product_review')]
#[AsResource(
    alias: 'sylius.product_review',
    section: 'backend',
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin',
    vars: [
        'subheader' => 'sylius.ui.manage_reviews_of_your_products',
        'templates' => ['form' => 'backend/product_review/_form.html.twig'],
    ],
    operations: [
        new Index(grid: 'sylius_backend_product_review', vars: ['icon' => 'star']),
        new Update(redirectToRoute: 'sylius_backend_product_review_update'),
        new Delete(),
    ],
)]
#[AsResource(
    alias: 'sylius.product_review',
    section: 'frontend',
    operations: [
        new Index(
            path: '/products/{slug}/reviews/',
            template: 'frontend/product_review/index.html.twig',
            grid: 'sylius_frontend_product_review',
        ),
        new Create(
            path: '/products/{slug}/reviews/new',
            template: 'frontend/product_review/create.html.twig',
            formOptions: ['validation_groups' => ['sylius', 'sylius_review']],
            factoryMethod: 'createForSubjectWithReviewer',
            factoryArguments: [
                'subject' => "@=throw_not_found_on_null(sylius_repositories.get('sylius.repository.product').findOneBySlug(request.getLocale(), request.attributes.get('slug')))",
                'reviewer' => '@=user ? user.getCustomer() : null',
            ],
            redirectToRoute: 'sylius_frontend_product_show',
            redirectArguments: ['slug' => 'resource.reviewSubject.slug'],
        ),
        new Update(
            path: '/products/{slug}/reviews/{id}/update',
            template: 'frontend/product_review/update.html.twig',
            repositoryMethod: 'findOneBy',
            repositoryArguments: [
                'criteria' => "@={'id': request.attributes.get('id'), 'author': user ? user.getCustomer() : null}",
            ],
            redirectToRoute: 'sylius_frontend_product_show',
            redirectArguments: ['slug' => 'resource.reviewSubject.slug'],
        ),
    ],
)]
class ProductReview extends Review
{
    /**
     * @var string
     */
    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    protected $code;

    /**
     * ProductReview constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->status = ReviewInterface::STATUS_ACCEPTED;
        $this->code = uniqid('product_review_');
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }
}
