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
use App\Entity\PersonImage;
use App\Entity\Taxon;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class PersonTest extends TestCase
{
    private Person $sut;

    protected function setUp(): void
    {
        $this->sut = new Person();
    }

    public function testImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testHasCodeByDefault(): void
    {
        $this->assertNotNull($this->sut->getCode());
    }

    public function testItsCodeIsMutable(): void
    {
        $this->sut->setCode('XYZ');

        $this->assertSame('XYZ', $this->sut->getCode());
    }

    public function testItsSlugIsMutable(): void
    {
        $this->sut->setSlug('darth-vader');

        $this->assertSame('darth-vader', $this->sut->getSlug());
    }

    public function testHasNoLastNameByDefault(): void
    {
        $this->assertSame(null, $this->sut->getLastName());
    }

    public function testItsLastNameIsMutable(): void
    {
        $this->sut->setLastName("Vader");

        $this->assertSame("Vader", $this->sut->getLastName());
    }

    public function testHasNoFirstNameByDefault(): void
    {
        $this->assertSame(null, $this->sut->getFirstName());
    }

    public function testItsFirstNameIsMutable(): void
    {
        $this->sut->setFirstName("Darth");

        $this->assertSame("Darth", $this->sut->getFirstName());
    }

    public function testCanGetFullName(): void
    {
        $this->sut->setFirstName('Edward');
        $this->sut->setLastName('Kenway');

        $this->assertSame('Edward Kenway', $this->sut->getFullName());

        $this->sut->setFirstName(null);
        $this->assertSame('Kenway', $this->sut->getFullName());
    }

    public function testHasNoWebsiteByDefault(): void
    {
        $this->assertSame(null, $this->sut->getWebsite());
    }

    public function testItsWebsiteIsMutable(): void
    {
        $this->sut->setWebsite("http://example.com");

        $this->assertSame("http://example.com", $this->sut->getWebsite());
    }

    public function testHasNoDescriptionByDefault(): void
    {
        $this->assertSame(null, $this->sut->getDescription());
    }

    public function testItsDescriptionIsMutable(): void
    {
        $this->sut->setDescription("I am your father");

        $this->assertSame("I am your father", $this->sut->getDescription());
    }

    public function testInitializesProductCountToZeroByDefault(): void
    {
        $this->assertSame(0, $this->sut->getProductCount());
    }

    public function testItsProductCountIsMutable(): void
    {
        $this->sut->setProductCount(666);

        $this->assertSame(666, $this->sut->getProductCount());
    }

    public function testItsProductCountAsArtistIsMutable(): void
    {
        $this->sut->setProductCountAsArtist(666);

        $this->assertSame(666, $this->sut->getProductCountAsArtist());
    }

    public function testItsProductCountAsDesignerIsMutable(): void
    {
        $this->sut->setProductCountAsDesigner(666);

        $this->assertSame(666, $this->sut->getProductCountAsDesigner());
    }

    public function testItsProductCountAsPublisherIsMutable(): void
    {
        $this->sut->setProductCountAsPublisher(666);

        $this->assertSame(666, $this->sut->getProductCountAsPublisher());
    }

    public function testInitializesImagesCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->sut->getImages());
    }

    public function testAddsImage(): void
    {
        $image = $this->createMock(PersonImage::class);
        $image->expects($this->once())->method('setPerson')->with($this->sut);

        $this->sut->addImage($image);

        $this->assertTrue($this->sut->hasImage($image));
    }

    public function testRemovesImage(): void
    {
        $image = $this->createStub(PersonImage::class);

        $this->sut->addImage($image);
        $this->sut->removeImage($image);

        $this->assertFalse($this->sut->hasImage($image));
    }

    public function testHasNoFirstImageByDefault(): void
    {
        $this->assertSame(null, $this->sut->getFirstImage());
    }

    public function testCanGetFirstImage(): void
    {
        $firstImage = $this->createStub(PersonImage::class);
        $secondImage = $this->createStub(PersonImage::class);

        $this->sut->addImage($firstImage);
        $this->sut->addImage($secondImage);

        $this->assertSame($firstImage, $this->sut->getFirstImage());
    }

    public function testInitializesTaxonCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->sut->getTaxons());
    }

    public function testAddsTaxon(): void
    {
        $taxon = $this->createStub(TaxonInterface::class);

        $this->sut->addTaxon($taxon);

        $this->assertTrue($this->sut->hasTaxon($taxon));
    }

    public function testRemovesTaxon(): void
    {
        $taxon = $this->createStub(TaxonInterface::class);

        $this->sut->addTaxon($taxon);
        $this->sut->removeTaxon($taxon);

        $this->assertFalse($this->sut->hasTaxon($taxon));
    }

    public function testItsZoneIsMutable(): void
    {
        $rootZone = $this->createStub(TaxonInterface::class);
        $rootZone->method('getCode')->willReturn(Taxon::CODE_ZONE);
        $previousZone = $this->createStub(TaxonInterface::class);
        $previousZone->method('getRoot')->willReturn($rootZone);
        $newZone = $this->createStub(TaxonInterface::class);
        $newZone->method('getRoot')->willReturn($rootZone);

        $this->sut->setZone($previousZone);
        $this->assertSame($previousZone, $this->sut->getZone());

        $this->sut->setZone($newZone);
        $this->assertSame($newZone, $this->sut->getZone());
    }
}
