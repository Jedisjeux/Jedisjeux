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

use App\Entity\ProductVideo;
use App\Entity\ProductVideoImage;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class ProductVideoTest extends TestCase
{
    private ProductVideo $video;

    protected function setUp(): void
    {
        $this->video = new ProductVideo();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->video);
    }

    public function testItsTitleIsMutable(): void
    {
        $this->video->setTitle('SteamRollers');

        $this->assertSame('SteamRollers', $this->video->getTitle());
    }

    public function testItsPathIsMutable(): void
    {
        $this->video->setPath('https://www.youtube.com/embed/oyefFfCHIGs?rel=0');

        $this->assertSame('https://www.youtube.com/embed/oyefFfCHIGs?rel=0', $this->video->getPath());
    }

    public function testItsImageIsMutable(): void
    {
        $image = $this->createStub(ProductVideoImage::class);

        $this->video->setImage($image);

        $this->assertSame($image, $this->video->getImage());
    }

    public function testItsProductIsMutable(): void
    {
        $product = $this->createStub(ProductInterface::class);

        $this->video->setProduct($product);

        $this->assertSame($product, $this->video->getProduct());
    }

    public function testItsCreationAtIsMutable(): void
    {
        $createdAt = new \DateTime();

        $this->video->setCreatedAt($createdAt);

        $this->assertSame($createdAt, $this->video->getCreatedAt());
    }

    public function testItsUpdatedAtIsMutable(): void
    {
        $updatedAt = new \DateTime();

        $this->video->setUpdatedAt($updatedAt);

        $this->assertSame($updatedAt, $this->video->getUpdatedAt());
    }
}
