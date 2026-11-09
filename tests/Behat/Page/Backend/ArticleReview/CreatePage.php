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

namespace App\Tests\Behat\Page\Backend\ArticleReview;

use App\Tests\Behat\Bridge\Crud\AbstractCreatePage;

class CreatePage extends AbstractCreatePage
{
    public function getRouteName(): string
    {
        return 'sylius_backend_article_review_create';
    }

    public function rateIt(int $rating): void
    {
        $this->getDocument()->selectFieldOption('sylius_article_review[rating]', (string) $rating);
    }

    public function commentIt(string $comment): void
    {
        $this->getElement('comment')->setValue($comment);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'comment' => '#sylius_article_review_comment',
        ]);
    }
}
