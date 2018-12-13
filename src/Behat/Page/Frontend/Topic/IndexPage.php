<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\Topic;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

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
        return 'app_frontend_topic_index';
    }

    /**
     * @return int
     */
    public function countItems(): int
    {
        $topics = $this->getDocument()->findAll('css', '#topic-list .image-box');

        return count($topics);
    }

    /**
     * @return string
     */
    public function getFirstTopicTitleFromList()
    {
        $topicsList = $this->getDocument()->find('css', '#topic-list');

        return $topicsList->find('css', '.row:first-child h3')->getText();
    }

    /**
     * @param string $title
     *
     * @return bool
     */
    public function isTopicOnList($title)
    {
        return null !== $this->getDocument()->find('css', sprintf('#topic-list h3:contains("%s")', $title));
    }

    /**
     * @param string $comment
     *
     * @return bool
     */
    public function isLastPostComment(string $comment)
    {
        return null !== $this->getDocument()->find('css', sprintf('#topic-list p:contains("%s")', $comment));
    }
}
