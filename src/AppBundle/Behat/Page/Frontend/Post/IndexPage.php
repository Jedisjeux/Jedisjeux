<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Frontend\Post;

use AppBundle\Behat\Page\SymfonyPage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class IndexPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_post_index_by_topic';
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
