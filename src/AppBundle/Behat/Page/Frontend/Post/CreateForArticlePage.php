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

class CreateForArticlePage extends CreatePage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_article_post_create';
    }
}
