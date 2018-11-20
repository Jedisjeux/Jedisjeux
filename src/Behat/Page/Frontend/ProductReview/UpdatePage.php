<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\ProductReview;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class UpdatePage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'sylius_frontend_product_review_update';
    }

    /**
     * @param string|null $title
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function setTitle(?string $title)
    {
        $this->getElement('title')->setValue($title);
    }

    /**
     * @param string|null $comment
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function setComment(?string $comment)
    {
        $this->getElement('comment')->setValue($comment);
    }

    /**
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function submit()
    {
        $this->getDocument()->pressButton('Save changes');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'title' => '#sylius_product_review_title',
            'comment' => '#sylius_product_review_comment',
        ]);
    }
}
