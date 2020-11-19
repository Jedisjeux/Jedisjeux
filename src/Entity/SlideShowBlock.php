<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

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

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    /**
     * @return Block[]|Collection
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function hasBlock(Block $block): bool
    {
        return $this->blocks->contains($block);
    }

    public function addBlock(Block $block): void
    {
        if (!$this->hasBlock($block)) {
            $block->setSlideShowBlock($this);
            $this->blocks->add($block);
        }
    }

    public function removeBlock(Block $block): void
    {
        $this->blocks->removeElement($block);
    }
}
