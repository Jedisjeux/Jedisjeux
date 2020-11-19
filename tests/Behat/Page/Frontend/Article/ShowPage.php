<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Frontend\Article;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Behat\Mink\Element\NodeElement;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ShowPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_article_show';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getElement('title')->getText();
    }

    /**
     *
     */
    public function getPostWithComment(string $comment): ?NodeElement
    {
        // Remove tags to search on DOM
        $comment = strip_tags($comment);

        return $this->getDocument()->find('css', sprintf('#comments .comment:contains("%s")', $comment));
    }

    /**
     *
     */
    public function getRemoveButtonFromPostWithComment(string $comment): ?NodeElement
    {
        $postItem = $this->getPostWithComment($comment);
        Assert::notNull($postItem);

        return $postItem->find('css', 'button.btn-confirm');
    }

    /**
     * @param string $title
     *
     * @return bool
     */
    public function isArticleOnList($title)
    {
        return null !== $this->getDocument()->find('css', sprintf('#latest-articles h6:contains("%s")', $title));
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'title' => 'h1.page-title',
        ]);
    }
}
