<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_slideshow_block")
 */
class SlideShowBlock implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @var Collection|Block[]
     *
     * @ORM\OneToMany(targetEntity="Block", mappedBy="slideShowBlock")
     */
    protected $blocks;

    /**
     * SlideShowBlock constructor.
     */
    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    /**
     * @return Block[]|Collection
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param Block $block
     *
     * @return bool
     */
    public function hasBlock(Block $block)
    {
        return $this->blocks->contains($block);
    }

    /**
     * @param Block $block
     *
     * @return $this
     */
    public function addBlock(Block $block)
    {
        if (!$this->hasBlock($block)) {
            $block->setSlideShowBlock($this);
            $this->blocks->add($block);
        }

        return $this;
    }

    /**
     * @param Block $block
     *
     * @return $this
     */
    public function removeBlock(Block $block)
    {
        $this->blocks->removeElement($block);

        return $this;
    }
}
