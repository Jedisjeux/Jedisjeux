<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 03/02/2016
 * Time: 09:29
 */

namespace AppBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\StringBlock;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @PHPCR\Document(referenceable=true)
 */
class BlockquoteBlock extends StringBlock
{
    public function getType()
    {
        return 'app.block.blockquote';
    }

}