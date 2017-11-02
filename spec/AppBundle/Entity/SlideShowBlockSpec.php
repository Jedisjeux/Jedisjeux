<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\Block;
use AppBundle\Entity\SlideShowBlock;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Model\ResourceInterface;

class SlideShowBlockSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SlideShowBlock::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_initializes_blocks_collection_by_default()
    {
        $this->getBlocks()->shouldHaveType(Collection::class);
    }

    function it_adds_block(Block $block)
    {
        $this->addBlock($block);
        $this->hasBlock($block)->shouldReturn(true);
    }

    function it_removes_block(Block $block)
    {
        $this->addBlock($block);
        $this->removeBlock($block);
        $this->hasBlock($block)->shouldReturn(false);
    }
}
