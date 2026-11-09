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
use App\Entity\Product;
use App\Entity\ProductBarcode;
use App\Entity\ProductFile;
use App\Entity\ProductVariant;
use App\Entity\ProductVariantImage;
use App\Entity\ProductVideo;
use App\Entity\Taxon;
use App\Entity\YearAward;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\Product as BaseProduct;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class ProductTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        $this->product = new Product();
        $this->product->setCurrentLocale('en_US');
        $this->product->setFallbackLocale('en_US');
    }

    public function testItExtendsAProductModel(): void
    {
        $this->assertInstanceOf(BaseProduct::class, $this->product);
    }

    public function testItCallsParentConstructor(): void
    {
        $this->assertNotNull($this->product->getCreatedAt());
        $this->assertInstanceOf(Collection::class, $this->product->getAttributes());
        $this->assertInstanceOf(Collection::class, $this->product->getAssociations());
        $this->assertInstanceOf(Collection::class, $this->product->getVariants());
        $this->assertInstanceOf(Collection::class, $this->product->getOptions());
    }

    public function testItHasCodeByDefault(): void
    {
        $this->assertNotNull($this->product->getCode());
    }

    public function testItsStatusIsNewByDefault(): void
    {
        $this->assertSame(Product::STATUS_NEW, $this->product->getStatus());
    }

    public function testItsStatusIsMutable(): void
    {
        $this->product->setStatus(Product::PENDING_PUBLICATION);

        $this->assertSame(Product::PENDING_PUBLICATION, $this->product->getStatus());
    }

    public function testItsNameIsMutable(): void
    {
        $this->product->setName('Puerto Rico');

        $this->assertSame('Puerto Rico', $this->product->getName());
    }

    public function testItsUpdatesFirstVariantName(): void
    {
        $variant = $this->createMock(ProductVariant::class);
        $this->product->addVariant($variant);

        $variant->expects($this->once())->method('setName')->with('Puerto Rico');

        $this->product->setName('Puerto Rico');
    }

    public function testItsShortDescriptionIsMutable(): void
    {
        $this->product->setShortDescription('This is an awesome board game.');

        $this->assertSame('This is an awesome board game.', $this->product->getShortDescription());
    }

    public function testItsAverageRatingIsMutable(): void
    {
        $this->product->setAverageRating(7.3);

        $this->assertSame(7.3, $this->product->getAverageRating());
    }

    public function testItInitializesTaxonCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->product->getTaxons());
    }

    public function testItAddsTaxon(): void
    {
        $taxon = $this->createStub(TaxonInterface::class);

        $this->product->addTaxon($taxon);

        $this->assertTrue($this->product->hasTaxon($taxon));
    }

    public function testItRemovesTaxon(): void
    {
        $taxon = $this->createStub(TaxonInterface::class);

        $this->product->addTaxon($taxon);
        $this->product->removeTaxon($taxon);

        $this->assertFalse($this->product->hasTaxon($taxon));
    }

    public function testItsMainTaxonIsMutable(): void
    {
        $taxon = $this->createStub(TaxonInterface::class);

        $this->product->setMainTaxon($taxon);

        $this->assertSame($taxon, $this->product->getMainTaxon());
    }

    public function testItCanFilterTaxonsByCode(): void
    {
        $rootTaxon1 = $this->createStub(TaxonInterface::class);
        $rootTaxon1->method('getCode')->willReturn('xyz');
        $rootTaxon2 = $this->createStub(TaxonInterface::class);
        $rootTaxon2->method('getCode')->willReturn('666');
        $taxon1 = $this->createStub(TaxonInterface::class);
        $taxon1->method('getRoot')->willReturn($rootTaxon1);
        $taxon2 = $this->createStub(TaxonInterface::class);
        $taxon2->method('getRoot')->willReturn($rootTaxon2);

        $this->product->addTaxon($taxon1);
        $this->product->addTaxon($taxon2);

        $taxons = $this->product->getTaxons('xyz');

        $this->assertTrue($taxons->contains($taxon1));
        $this->assertFalse($taxons->contains($taxon2));
    }

    public function testItHasNoMinAgeByDefault(): void
    {
        $this->assertNull($this->product->getMinAge());
    }

    public function testItsMinAgeIsMutable(): void
    {
        $this->product->setMinAge(7);

        $this->assertSame(7, $this->product->getMinAge());
    }

    public function testItHasNoMinPlayerCountByDefault(): void
    {
        $this->assertNull($this->product->getMinPlayerCount());
    }

    public function testItsMinPlayerCountIsMutable(): void
    {
        $this->product->setMinPlayerCount(2);

        $this->assertSame(2, $this->product->getMinPlayerCount());
    }

    public function testItHasNoMaxPlayerCountByDefault(): void
    {
        $this->assertNull($this->product->getMaxPlayerCount());
    }

    public function testItsMaxPlayerCountIsMutable(): void
    {
        $this->product->setMaxPlayerCount(4);

        $this->assertSame(4, $this->product->getMaxPlayerCount());
    }

    public function testItHasNoMinDurationByDefault(): void
    {
        $this->assertNull($this->product->getMinDuration());
    }

    public function testItsMinDurationIsMutable(): void
    {
        $this->product->setMinDuration(30);

        $this->assertSame(30, $this->product->getMinDuration());
    }

    public function testItHasNoMaxDurationByDefault(): void
    {
        $this->assertNull($this->product->getMaxDuration());
    }

    public function testItsMaxDurationIsMutable(): void
    {
        $this->product->setMaxDuration(180);

        $this->assertSame(180, $this->product->getMaxDuration());
    }

    public function testItsDurationIsNotByPlayerByDefault(): void
    {
        $this->assertFalse($this->product->isDurationByPlayer());
    }

    public function testItsDurationByPlayerIsMutable(): void
    {
        $this->product->setDurationByPlayer(true);

        $this->assertTrue($this->product->isDurationByPlayer());
    }

    public function testItHasNoBoxContentByDefault(): void
    {
        $this->assertNull($this->product->getBoxContent());
    }

    public function testItsBoxContentIsMutable(): void
    {
        $this->product->setBoxContent('rulebook, meeples');

        $this->assertSame('rulebook, meeples', $this->product->getBoxContent());
    }

    public function testItHasZeroViewCountByDefault(): void
    {
        $this->assertSame(0, $this->product->getViewCount());
    }

    public function testItsViewCountIsMutable(): void
    {
        $this->product->setViewCount(666);

        $this->assertSame(666, $this->product->getViewCount());
    }

    public function testItCanGetReleasedAt(): void
    {
        $releaseDate = new \DateTime();
        $variant = $this->createStub(ProductVariant::class);
        $variant->method('getReleasedAt')->willReturn($releaseDate);

        $this->product->addVariant($variant);

        $this->assertSame($releaseDate, $this->product->getReleasedAt());
    }

    public function testItHasZeroReviewCountByDefault(): void
    {
        $this->assertSame(0, $this->product->getReviewCount());
    }

    public function testItsReviewCountIsMutable(): void
    {
        $this->product->setReviewCount(666);

        $this->assertSame(666, $this->product->getReviewCount());
    }

    public function testItHasZeroCommentedReviewCountByDefault(): void
    {
        $this->assertSame(0, $this->product->getCommentedReviewCount());
    }

    public function testItsCommentedReviewCountIsMutable(): void
    {
        $this->product->setCommentedReviewCount(666);

        $this->assertSame(666, $this->product->getCommentedReviewCount());
    }

    public function testItInitializesReviewsCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->product->getReviews());
    }

    public function testItAddsReview(): void
    {
        $review = $this->createStub(ReviewInterface::class);

        $this->product->addReview($review);

        $this->assertTrue($this->product->hasReview($review));
    }

    public function testItRemovesReview(): void
    {
        $review = $this->createStub(ReviewInterface::class);

        $this->product->addReview($review);
        $this->product->removeReview($review);

        $this->assertFalse($this->product->hasReview($review));
    }

    public function testItCanGetRatings(): void
    {
        $review = $this->createStub(ReviewInterface::class);
        $review->method('getComment')->willReturn('This is an awesome product');
        $rating = $this->createStub(ReviewInterface::class);
        $rating->method('getComment')->willReturn(null);

        $this->product->addReview($rating);
        $this->product->addReview($review);

        $this->assertContains($rating, $this->product->getRatings());
        $this->assertNotContains($review, $this->product->getRatings());
    }

    public function testItCanGetCommentedReviews(): void
    {
        $review = $this->createStub(ReviewInterface::class);
        $review->method('getComment')->willReturn('This is an awesome product');
        $rating = $this->createStub(ReviewInterface::class);
        $rating->method('getComment')->willReturn(null);

        $this->product->addReview($rating);
        $this->product->addReview($review);

        $this->assertContains($review, $this->product->getCommentedReviews());
        $this->assertNotContains($rating, $this->product->getCommentedReviews());
    }

    public function testItInitializesArticleCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->product->getArticles());
    }

    public function testItInitializesGamePlayCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->product->getGamePlays());
    }

    public function testItInitializesVideoCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->product->getVideos());
    }

    public function testItAddsVideos(): void
    {
        $video = $this->createMock(ProductVideo::class);
        $video->expects($this->once())->method('setProduct')->with($this->product);

        $this->product->addVideo($video);

        $this->assertTrue($this->product->hasVideo($video));
    }

    public function testItRemovesVideos(): void
    {
        $products = [];
        $video = $this->createMock(ProductVideo::class);
        $video->expects($this->exactly(2))->method('setProduct')->willReturnCallback(
            static function (?Product $product) use (&$products): void {
                $products[] = $product;
            },
        );

        $this->product->addVideo($video);
        $this->product->removeVideo($video);

        $this->assertFalse($this->product->hasVideo($video));
        $this->assertSame([$this->product, null], $products);
    }

    public function testItInitializesFileCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->product->getFiles());
    }

    public function testItAddsFiles(): void
    {
        $file = $this->createMock(ProductFile::class);
        $file->expects($this->once())->method('setProduct')->with($this->product);

        $this->product->addFile($file);

        $this->assertTrue($this->product->hasFile($file));
    }

    public function testItRemovesFiles(): void
    {
        $products = [];
        $file = $this->createMock(ProductFile::class);
        $file->expects($this->exactly(2))->method('setProduct')->willReturnCallback(
            static function (?Product $product) use (&$products): void {
                $products[] = $product;
            },
        );

        $this->product->addFile($file);
        $this->product->removeFile($file);

        $this->assertFalse($this->product->hasFile($file));
        $this->assertSame([$this->product, null], $products);
    }

    public function testItHasNoImagesByDefault(): void
    {
        $variant = $this->createStub(ProductVariant::class);
        $variant->method('getImages')->willReturn(new ArrayCollection());

        $this->product->addVariant($variant);

        $this->assertCount(0, $this->product->getImages());
    }

    public function testItCanGetImages(): void
    {
        $image = $this->createStub(ProductVariantImage::class);
        $variant = $this->createStub(ProductVariant::class);
        $variant->method('getImages')->willReturn(new ArrayCollection([$image]));

        $this->product->addVariant($variant);

        $this->assertContains($image, $this->product->getImages());
    }

    public function testItHasNoMainImageByDefault(): void
    {
        $this->assertNull($this->product->getMainImage());
    }

    public function testItCanGetMainImage(): void
    {
        $image = $this->createStub(ProductVariantImage::class);
        $variant = $this->createStub(ProductVariant::class);
        $variant->method('getMainImage')->willReturn($image);

        $this->product->addVariant($variant);

        $this->assertSame($image, $this->product->getMainImage());
    }

    public function testItHasNoMaterialImageByDefault(): void
    {
        $this->assertNull($this->product->getMaterialImage());
    }

    public function testItCanGetMaterialImage(): void
    {
        $image = $this->createStub(ProductVariantImage::class);
        $variant = $this->createStub(ProductVariant::class);
        $variant->method('getMaterialImage')->willReturn($image);

        $this->product->addVariant($variant);

        $this->assertSame($image, $this->product->getMaterialImage());
    }

    public function testItCanGetDesigners(): void
    {
        $designer = $this->createStub(Person::class);
        $variant = $this->createStub(ProductVariant::class);
        $variant->method('getDesigners')->willReturn(new ArrayCollection([$designer]));

        $this->product->addVariant($variant);

        $this->assertContains($designer, $this->product->getDesigners());
    }

    public function testItCanGetArtists(): void
    {
        $artist = $this->createStub(Person::class);
        $variant = $this->createStub(ProductVariant::class);
        $variant->method('getArtists')->willReturn(new ArrayCollection([$artist]));

        $this->product->addVariant($variant);

        $this->assertContains($artist, $this->product->getArtists());
    }

    public function testItCanGetPublishers(): void
    {
        $publisher = $this->createStub(Person::class);
        $variant = $this->createStub(ProductVariant::class);
        $variant->method('getPublishers')->willReturn(new ArrayCollection([$publisher]));

        $this->product->addVariant($variant);

        $this->assertContains($publisher, $this->product->getPublishers());
    }

    public function testItAddsMechanisms(): void
    {
        $taxon = $this->createTaxonWithRootCode(Taxon::CODE_MECHANISM);

        $this->product->addMechanism($taxon);

        $this->assertContains($taxon, $this->product->getMechanisms());
    }

    public function testItRemovesMechanisms(): void
    {
        $taxon = $this->createTaxonWithRootCode(Taxon::CODE_MECHANISM);

        $this->product->addMechanism($taxon);
        $this->product->removeMechanism($taxon);

        $this->assertNotContains($taxon, $this->product->getMechanisms());
    }

    public function testItAddsThemes(): void
    {
        $taxon = $this->createTaxonWithRootCode(Taxon::CODE_THEME);

        $this->product->addTheme($taxon);

        $this->assertContains($taxon, $this->product->getThemes());
    }

    public function testItRemovesThemes(): void
    {
        $taxon = $this->createTaxonWithRootCode(Taxon::CODE_THEME);

        $this->product->addTheme($taxon);
        $this->product->removeTheme($taxon);

        $this->assertNotContains($taxon, $this->product->getThemes());
    }

    public function testItInitializesBarcodeCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->product->getBarcodes());
    }

    public function testItAddsBarcode(): void
    {
        $barcode = $this->createMock(ProductBarcode::class);
        $barcode->expects($this->once())->method('setProduct')->with($this->product);

        $this->product->addBarcode($barcode);

        $this->assertTrue($this->product->hasBarcode($barcode));
    }

    public function testItRemovesBarcode(): void
    {
        $products = [];
        $barcode = $this->createMock(ProductBarcode::class);
        $barcode->expects($this->exactly(2))->method('setProduct')->willReturnCallback(
            static function (?Product $product) use (&$products): void {
                $products[] = $product;
            },
        );

        $this->product->addBarcode($barcode);
        $this->product->removeBarcode($barcode);

        $this->assertFalse($this->product->hasBarcode($barcode));
        $this->assertSame([$this->product, null], $products);
    }

    public function testItInitializesYearAwardCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->product->getYearAwards());
    }

    public function testItAddsYearAwards(): void
    {
        $yearAward = $this->createMock(YearAward::class);
        $yearAward->expects($this->once())->method('setProduct')->with($this->product);

        $this->product->addYearAward($yearAward);

        $this->assertTrue($this->product->hasYearAward($yearAward));
    }

    public function testItRemovesYearAwards(): void
    {
        $products = [];
        $yearAward = $this->createMock(YearAward::class);
        $yearAward->expects($this->exactly(2))->method('setProduct')->willReturnCallback(
            static function (?Product $product) use (&$products): void {
                $products[] = $product;
            },
        );

        $this->product->addYearAward($yearAward);
        $this->product->removeYearAward($yearAward);

        $this->assertFalse($this->product->hasYearAward($yearAward));
        $this->assertSame([$this->product, null], $products);
    }

    private function createTaxonWithRootCode(string $rootCode): TaxonInterface
    {
        $rootTaxon = $this->createStub(TaxonInterface::class);
        $rootTaxon->method('getCode')->willReturn($rootCode);
        $taxon = $this->createStub(TaxonInterface::class);
        $taxon->method('getRoot')->willReturn($rootTaxon);

        return $taxon;
    }
}
