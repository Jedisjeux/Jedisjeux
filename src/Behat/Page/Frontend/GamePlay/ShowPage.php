<?php
/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\GamePlay;

use App\Behat\Page\SymfonyPage;
use Behat\Mink\Element\NodeElement;
use Webmozart\Assert\Assert;

class ShowPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_game_play_show';
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
}
