<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\ProductReview;

use Monofony\Bridge\Behat\Crud\AbstractUpdatePage;

class UpdatePage extends AbstractUpdatePage
{
    public function getRouteName(): string
    {
        return 'sylius_backend_product_review_update';
    }

    /**
     * @param string $title
     */
    public function changeTitle($title)
    {
        $this->getElement('title')->setValue($title);
    }

    /**
     * @param string $comment
     */
    public function changeComment($comment)
    {
        $this->getElement('comment')->setValue($comment);
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
