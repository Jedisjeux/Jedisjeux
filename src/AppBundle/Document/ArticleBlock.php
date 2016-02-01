<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 31/01/16
 * Time: 19:33
 */

namespace AppBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @PHPCR\Document(referenceable=true)
 */
class ArticleBlock extends SimpleBlock
{
    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_TOP = 'top';

    /**
     * @var string
     *
     * @PHPCR\String(nullable=true)
     */
    protected $imagePosition;

    /**
     * @return string
     */
    public function getImagePosition()
    {
        return $this->imagePosition;
    }

    /**
     * @param string $imagePosition
     *
     * @return $this
     */
    public function setImagePosition($imagePosition)
    {
        $this->imagePosition = $imagePosition;

        return $this;
    }
}