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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ContainerBlock;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @PHPCR\Document(referenceable=true)
 */
class ArticleContent extends ContainerBlock implements ResourceInterface
{
    /**
     * state constants
     */
    const WRITING = "writing";
    const PENDING_REVIEW = "pending_review";
    const PENDING_PUBLICATION = "pending_publication";
    const PUBLISHED = "published";


    /**
     * @var string
     *
     * @PHPCR\Field(type="string", nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @PHPCR\Field(type="string", nullable=false)
     */
    protected $state = null;

    /**
     * @var ImagineBlock
     *
     * @PHPCR\Child()
     */
    protected $mainImage;

    /**
     * @PHPCR\Children(filter="block*")
     */
    protected $blocks;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->state = self::WRITING;
        $this->publishable = false;
        $this->blocks = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return ImagineBlock
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * @param ImagineBlock $mainImage
     *
     * @return $this
     */
    public function setMainImage(ImagineBlock $mainImage = null)
    {
        $mainImage->setParentDocument($this);
        $mainImage->setName('mainImage');
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param ContainerBlock $block
     *
     * @return bool
     */
    public function hasBlock(ContainerBlock $block)
    {
        return $this->blocks->contains($block);
    }

    /**
     * @param ContainerBlock $block
     *
     * @return $this
     */
    public function addBlock(ContainerBlock $block)
    {
        if (!$this->hasBlock($block)) {
            $this->blocks->add($block);
        }

        return $this;
    }

    /**
     * @param ContainerBlock $block
     *
     * @return $this
     */
    public function removeBlock(ContainerBlock $block)
    {
        $this->blocks->removeElement($block);

        return $this;
    }
}