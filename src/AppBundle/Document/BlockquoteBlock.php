<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 03/02/2016
 * Time: 09:29
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
    public function getType()
    {
        return 'app.block.blockquote';
    }

}