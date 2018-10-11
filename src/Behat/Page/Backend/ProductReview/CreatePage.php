<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Backend\ProductReview;

use App\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreatePage extends BaseCreatePage
{
    /**
     * @param string $title
     */
    public function specifyTitle($title)
    {
        $this->getElement('title')->setValue($title);
    }

    /**
     * @param string $comment
     */
    public function specifyComment($comment)
    {
        $this->getElement('comment')->setValue($comment);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'title' => '#sylius_product_review_title',
            'comment' => '#sylius_product_review_comment',
        ]);
    }
}
