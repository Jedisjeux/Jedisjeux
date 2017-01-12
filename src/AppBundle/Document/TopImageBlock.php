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

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @PHPCR\Document(referenceable=true)
 */
class TopImageBlock extends SingleImageBlock
{
    /**
     * TopImageBlock constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->imagePosition = SingleImageBlock::POSITION_TOP;
    }
}
