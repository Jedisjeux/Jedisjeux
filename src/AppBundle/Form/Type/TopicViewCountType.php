<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicViewCountType extends AbstractViewCountType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_topic_view_count';
    }
}
