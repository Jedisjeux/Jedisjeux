<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Updater;

use AppBundle\Entity\Topic;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostCountByTopicUpdater
{
    public function update(Topic $topic)
    {
        $postCount = $topic->getPosts()->count();
        $topic->setPostCount($postCount);
    }
}
