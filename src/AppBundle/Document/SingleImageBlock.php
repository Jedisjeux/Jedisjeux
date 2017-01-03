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
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @PHPCR\Document(referenceable=true)
 */
class SingleImageBlock extends SlideshowBlock implements ResourceInterface
{
    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_TOP = 'top';

    /**
     * @var string
     *
     * @PHPCR\Field(type="string", nullable=false)
     */
    protected $body;

    /**
     * @var string
     *
     * @PHPCR\Field(type="string", nullable=true)
     */
    protected $class;

    /**
     * @var string
     *
     * @PHPCR\Field(type="string", nullable=true)
     */
    protected $imagePosition;

    /**
     * @var ImagineBlock
     *
     * @PHPCR\Child()
     */
    protected $imagineBlock;

    /**
     * SingleImageBlock constructor.
     */
    public function __construct()
    {
        parent::__construct(uniqid('block_'));
    }

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

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function getType()
    {
        return 'app.block.single_image';
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return null|ImagineBlock
     */
    public function getImagineBlock()
    {
        return $this->imagineBlock;
    }

    /**
     * @param ImagineBlock $imagineBlock
     *
     * @return $this
     */
    public function setImagineBlock(ImagineBlock $imagineBlock = null)
    {
        $imagineBlock->setParentDocument($this);
        $imagineBlock->setName('imagineBlock');
        $this->imagineBlock = $imagineBlock;

        return $this;
    }
}
