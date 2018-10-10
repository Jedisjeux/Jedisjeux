<?php

/*
 * This file is part of jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

class AppEvents
{
    private function __construct()
    {
    }

    const TOPIC_PRE_CREATE = 'app.topic.pre_create';

    const POST_PRE_CREATE = 'app.post.pre_create';

    const POST_POST_CREATE = 'app.post.post_create';
}
