<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class AvatarType extends AbstractImageType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_avatar';
    }

}
