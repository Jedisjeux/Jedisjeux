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

use App\Entity\AbstractImage;
use App\Entity\BlockImage;
use PHPUnit\Framework\TestCase;

final class BlockImageTest extends TestCase
{
    private BlockImage $sut;

    protected function setUp(): void
    {
        $this->sut = new BlockImage();
    }

    public function testItExtendsAbstractImage(): void
    {
        $this->assertInstanceOf(AbstractImage::class, $this->sut);
    }

    public function testItsLabelIsMutable(): void
    {
        $this->sut->setLabel('This is an awesome image.');
        $this->assertSame('This is an awesome image.', $this->sut->getLabel());
    }

    public function testItsLinkUrlIsMutable(): void
    {
        $this->sut->setLinkUrl('http://example.com');
        $this->assertSame('http://example.com', $this->sut->getLinkUrl());
    }
}
