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

use App\Entity\Person;
use App\Entity\ProductBox;
use App\Entity\ProductVariant;
use App\Entity\ProductVariantImage;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductVariant as BaseProductVariant;

final class ProductVariantTest extends TestCase
{
    private ProductVariant $variant;

    protected function setUp(): void
    {
        $this->variant = new ProductVariant();
    }

    public function testItExtendsAProductVariantModel(): void
    {
        $this->assertInstanceOf(BaseProductVariant::class, $this->variant);
    }

    public function testItInitializesCreationDateByDefault(): void
    {
        $this->assertInstanceOf(\DateTimeInterface::class, $this->variant->getCreatedAt());
    }

    public function testItsCreationDateIsMutable(): void
    {
        $date = new \DateTime();

        $this->variant->setCreatedAt($date);

        $this->assertSame($date, $this->variant->getCreatedAt());
    }

    public function testItHasNoReleasedAtByDefault(): void
    {
        $this->assertNull($this->variant->getReleasedAt());
    }

    public function testItsReleasedAtIsMutable(): void
    {
        $releasedAt = new \DateTime();

        $this->variant->setReleasedAt($releasedAt);

        $this->assertSame($releasedAt, $this->variant->getReleasedAt());
    }

    public function testItHasNoReleasedAtPrecisionByDefault(): void
    {
        $this->assertNull($this->variant->getReleasedAtPrecision());
    }

    public function testItsReleasedAtPrecisionIsMutable(): void
    {
        $this->variant->setReleasedAtPrecision(ProductVariant::RELEASED_AT_PRECISION_ON_MONTH);

        $this->assertSame(ProductVariant::RELEASED_AT_PRECISION_ON_MONTH, $this->variant->getReleasedAtPrecision());
    }

    public function testItInitializesImagesCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->variant->getImages());
    }

    public function testItAddsImage(): void
    {
        $image = $this->createMock(ProductVariantImage::class);
        $image->expects($this->once())->method('setVariant')->with($this->variant);

        $this->variant->addImage($image);

        $this->assertTrue($this->variant->hasImage($image));
    }

    public function testItRemovesImage(): void
    {
        $image = $this->createStub(ProductVariantImage::class);

        $this->variant->addImage($image);
        $this->variant->removeImage($image);

        $this->assertFalse($this->variant->hasImage($image));
    }

    public function testItInitializesDesignersCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->variant->getDesigners());
    }

    public function testItAddsDesigner(): void
    {
        $designer = $this->createStub(Person::class);

        $this->variant->addDesigner($designer);

        $this->assertTrue($this->variant->hasDesigner($designer));
    }

    public function testItRemovesDesigner(): void
    {
        $designer = $this->createStub(Person::class);

        $this->variant->addDesigner($designer);
        $this->variant->removeDesigner($designer);

        $this->assertFalse($this->variant->hasDesigner($designer));
    }

    public function testItInitializesArtistsCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->variant->getArtists());
    }

    public function testItAddsArtist(): void
    {
        $artist = $this->createStub(Person::class);

        $this->variant->addArtist($artist);

        $this->assertTrue($this->variant->hasArtist($artist));
    }

    public function testItRemovesArtist(): void
    {
        $artist = $this->createStub(Person::class);

        $this->variant->addArtist($artist);
        $this->variant->removeArtist($artist);

        $this->assertFalse($this->variant->hasArtist($artist));
    }

    public function testItInitializesPublishersCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->variant->getPublishers());
    }

    public function testItAddsPublisher(): void
    {
        $publisher = $this->createStub(Person::class);

        $this->variant->addPublisher($publisher);

        $this->assertTrue($this->variant->hasPublisher($publisher));
    }

    public function testItRemovesPublisher(): void
    {
        $publisher = $this->createStub(Person::class);

        $this->variant->addPublisher($publisher);
        $this->variant->removePublisher($publisher);

        $this->assertFalse($this->variant->hasPublisher($publisher));
    }

    public function testItInitializesBoxesCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->variant->getBoxes());
    }

    public function testItAddsBoxes(): void
    {
        $box = $this->createStub(ProductBox::class);

        $this->variant->addBox($box);

        $this->assertTrue($this->variant->hasBox($box));
    }

    public function testItRemovesBoxes(): void
    {
        $box = $this->createStub(ProductBox::class);

        $this->variant->addBox($box);
        $this->variant->removeBox($box);

        $this->assertFalse($this->variant->hasBox($box));
    }

    public function testItHasNoEnabledBoxByDefault(): void
    {
        $this->assertNull($this->variant->getEnabledBox());
    }

    public function testItCanGetEnabledBox(): void
    {
        $disabledBox = $this->createStub(ProductBox::class);
        $disabledBox->method('isEnabled')->willReturn(false);
        $enabledBox = $this->createStub(ProductBox::class);
        $enabledBox->method('isEnabled')->willReturn(true);

        $this->variant->addBox($disabledBox);
        $this->variant->addBox($enabledBox);

        $this->assertSame($enabledBox, $this->variant->getEnabledBox());
    }
}
