<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\StringBlock;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @PHPCR\Document(referenceable=true)
 */
class BlockquoteBlock extends StringBlock implements ResourceInterface
{
    /**
     * BlockquoteBlock constructor.
     */
    public function __construct()
    {
        $this->name = uniqid('block_');
    }

    public function getType()
    {
        return 'app.block.blockquote';
    }

}