<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\Post;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Behat\Mink\Element\NodeElement;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class IndexPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_post_index_by_topic';
    }

    /**
     * @return int
     */
    public function countComments(): int
    {
        $comments = $this->getDocument()->findAll('css', '#comments .comment');

        return count($comments);
    }

    /**
     * @param string $comment
     *
     * @return NodeElement|null
     */
    public function getPostWithComment(string $comment): ?NodeElement
    {
        // Remove tags to search on DOM
        $comment = strip_tags($comment);

        return $this->getDocument()->find('css', sprintf('#comments .comment:contains("%s")', $comment));
    }

    /**
     * @param string $comment
     *
     * @return NodeElement|null
     */
    public function getRemoveButtonFromPostWithComment(string $comment): ?NodeElement
    {
        $postItem = $this->getPostWithComment($comment);
        Assert::notNull($postItem);

        return $postItem->find('css', 'button.btn-confirm');
    }

    /**
     * @return int
     */
    public function countItems(): int
    {
        $postList = $this->getDocument()->find('css', '#comments');

        $posts = $postList->findAll('css', '.form-group');

        return count($posts);
    }
}
