<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Block;
use App\Entity\SlideShowBlock;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\ResourceInterface;

final class SlideShowBlockTest extends TestCase
{
    private SlideShowBlock $slideShowBlock;

    protected function setUp(): void
    {
        $this->slideShowBlock = new SlideShowBlock();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->slideShowBlock);
    }

    public function testItInitializesBlocksCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->slideShowBlock->getBlocks());
    }

    public function testItAddsBlock(): void
    {
        $block = $this->createMock(Block::class);
        $block->expects($this->once())->method('setSlideShowBlock')->with($this->slideShowBlock);

        $this->slideShowBlock->addBlock($block);

        $this->assertTrue($this->slideShowBlock->hasBlock($block));
    }

    public function testItRemovesBlock(): void
    {
        $block = $this->createStub(Block::class);

        $this->slideShowBlock->addBlock($block);
        $this->slideShowBlock->removeBlock($block);

        $this->assertFalse($this->slideShowBlock->hasBlock($block));
    }
}
