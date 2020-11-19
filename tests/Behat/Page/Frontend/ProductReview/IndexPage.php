<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Frontend\ProductReview;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class IndexPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'sylius_frontend_product_review_index';
    }

    /**
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function countReviews(): int
    {
        return count($this->getElement('reviews')->findAll('css', '.comment'));
    }

    /**
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function hasNoReviewsMessage(): bool
    {
        $reviewsContainerText = $this->getElement('reviews')->getText();

        return false !== strpos($reviewsContainerText, 'There are no reviews');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'reviews' => '#product-reviews',
        ]);
    }
}
