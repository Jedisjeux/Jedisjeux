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

use App\Entity\Article;
use App\Entity\ArticleImage;
use App\Entity\ArticleReview;
use App\Entity\Block;
use App\Entity\Taxon;
use App\Entity\Topic;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class ArticleTest extends TestCase
{
    private Article $sut;

    protected function setUp(): void
    {
        $this->sut = new Article();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testItsCodeIsMutable(): void
    {
        $this->sut->setCode("ARTICLE1");
        $this->assertSame("ARTICLE1", $this->sut->getCode());
    }

    public function testItHasNoTitleByDefault(): void
    {
        $this->assertNull($this->sut->getTitle());
    }

    public function testItsTitleIsMutable(): void
    {
        $this->sut->setTitle("Awesome title");
        $this->assertSame("Awesome title", $this->sut->getTitle());
    }

    public function testItCanGetName(): void
    {
        $this->sut->setTitle('Awesome title');
        $this->assertSame('Awesome title', $this->sut->getName());
    }

    public function testItsSlugIsMutable(): void
    {
        $this->sut->setSlug("awesome-title");
        $this->assertSame("awesome-title", $this->sut->getSlug());
    }

    public function testItsNotPublishableByDefault(): void
    {
        $this->assertFalse($this->sut->isPublishable());
    }

    public function testItCanBePublishable(): void
    {
        $this->sut->setPublishable(false);
        $this->sut->setPublishable(true);
        $this->assertTrue($this->sut->isPublishable());
    }

    public function testItHasNoPublishStartDateByDefault(): void
    {
        $this->assertNull($this->sut->getPublishStartDate());
    }

    public function testItsPublishStartDateIsMutable(): void
    {
        $publishStartDate = new \DateTime();

        $this->sut->setPublishStartDate($publishStartDate);
        $this->assertSame($publishStartDate, $this->sut->getPublishStartDate());
    }

    public function testItHasNoPublishEndDateByDefault(): void
    {
        $this->assertNull($this->sut->getPublishEndDate());
    }

    public function testItsPublishEndDateIsMutable(): void
    {
        $publishEndDate = new \DateTime();

        $this->sut->setPublishEndDate($publishEndDate);
        $this->assertSame($publishEndDate, $this->sut->getPublishEndDate());
    }

    public function testItsStatusIsNewByDefault(): void
    {
        $this->assertSame(Article::STATUS_NEW, $this->sut->getStatus());
    }

    public function testItsStatusIsMutable(): void
    {
        $this->sut->setStatus(Article::STATUS_PENDING_PUBLICATION);
        $this->assertSame(Article::STATUS_PENDING_PUBLICATION, $this->sut->getStatus());
    }

    public function testItsShortDescriptionIsMutable(): void
    {
        $this->sut->setShortDescription("This is an awesome description.");
        $this->assertSame("This is an awesome description.", $this->sut->getShortDescription());
    }

    public function testItsAverageRatingIsMutable(): void
    {
        $this->sut->setAverageRating(7.66);
        $this->assertSame(7.66, $this->sut->getAverageRating());
    }

    public function testItsMaterialRatingIsMutable(): void
    {
        $this->sut->setMaterialRating(7.66);
        $this->assertSame(7.66, $this->sut->getMaterialRating());
    }

    public function testItsRulesRatingIsMutable(): void
    {
        $this->sut->setRulesRating(7.66);
        $this->assertSame(7.66, $this->sut->getRulesRating());
    }

    public function testItsLifetimeRatingIsMutable(): void
    {
        $this->sut->setLifetimeRating(7.66);
        $this->assertSame(7.66, $this->sut->getLifetimeRating());
    }

    public function testItsViewCountIsMutable(): void
    {
        $this->sut->setViewCount(42);
        $this->assertSame(42, $this->sut->getViewCount());
    }

    public function testItIsNotAReviewArticleByDefault(): void
    {
        $this->assertFalse($this->sut->isReviewArticle());
    }

    public function testItCouldBeAReviewArticle(): void
    {
        $taxon = $this->createStub(TaxonInterface::class);
        $taxon->method('getCode')->willReturn(Taxon::CODE_REVIEW_ARTICLE);

        $this->sut->setMainTaxon($taxon);
        $this->assertTrue($this->sut->isReviewArticle());
    }

    public function testItIsNotAReportArticleByDefault(): void
    {
        $this->assertFalse($this->sut->isReportArticle());
    }

    public function testItCouldBeAReportArticle(): void
    {
        $taxon = $this->createStub(TaxonInterface::class);
        $taxon->method('getCode')->willReturn(Taxon::CODE_REPORT_ARTICLE);

        $this->sut->setMainTaxon($taxon);
        $this->assertTrue($this->sut->isReportArticle());
    }

    public function testItHasNoMainImageByDefault(): void
    {
        $this->assertNull($this->sut->getMainImage());
    }

    public function testItsMainImageIsMutable(): void
    {
        $image = $this->createStub(ArticleImage::class);

        $this->sut->setMainImage($image);
        $this->assertSame($image, $this->sut->getMainImage());
    }

    public function testItHasNoMainTaxonByDefault(): void
    {
        $this->assertNull($this->sut->getMainTaxon());
    }

    public function testItsMainTaxonIsMutable(): void
    {
        $taxon = $this->createStub(TaxonInterface::class);

        $this->sut->setMainTaxon($taxon);
        $this->assertSame($taxon, $this->sut->getMainTaxon());
    }

    public function testItHasNoTopicByDefault(): void
    {
        $this->assertNull($this->sut->getTopic());
    }

    public function testItsTopicIsMutable(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->method('getArticle')->willReturn(null);
        $topic->expects($this->once())->method('setArticle')->with($this->sut);

        $this->sut->setTopic($topic);
        $this->assertSame($topic, $this->sut->getTopic());
    }

    public function testItInitializesBlocksCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->sut->getBlocks());
    }

    public function testItAddsBlock(): void
    {
        $block = $this->createMock(Block::class);
        $block->expects($this->once())->method('setArticle')->with($this->sut);

        $this->sut->addBlock($block);
        $this->assertTrue($this->sut->hasBlock($block));
    }

    public function testItRemovesBlock(): void
    {
        $block = $this->createStub(Block::class);

        $this->sut->addBlock($block);
        $this->sut->removeBlock($block);
        $this->assertFalse($this->sut->hasBlock($block));
    }

    public function testItInitializesReviewsCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->sut->getReviews());
    }

    public function testItAddsReview(): void
    {
        $review = $this->createMock(ArticleReview::class);
        $review->expects($this->once())->method('setReviewSubject')->with($this->sut);

        $this->sut->addReview($review);
        $this->assertTrue($this->sut->hasReview($review));
    }

    public function testItRemovesReview(): void
    {
        $review = $this->createStub(ArticleReview::class);

        $this->sut->addReview($review);
        $this->sut->removeReview($review);
        $this->assertFalse($this->sut->hasReview($review));
    }

    public function testItsProductIsMutable(): void
    {
        $product = $this->createStub(ProductInterface::class);

        $this->sut->setProduct($product);
        $this->assertSame($product, $this->sut->getProduct());
    }

    public function testItsAuthorIsMutable(): void
    {
        $author = $this->createStub(CustomerInterface::class);

        $this->sut->setAuthor($author);
        $this->assertSame($author, $this->sut->getAuthor());
    }
}
