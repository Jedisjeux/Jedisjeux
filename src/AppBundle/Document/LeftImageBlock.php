<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Document;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LeftImageBlock extends SingleImageBlock
{
    /**
     * LeftImageBlock constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->imagePosition = SingleImageBlock::POSITION_LEFT;
    }
}
