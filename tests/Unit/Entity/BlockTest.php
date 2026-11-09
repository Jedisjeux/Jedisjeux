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
use App\Entity\Block;
use App\Entity\BlockImage;
use App\Entity\SlideShowBlock;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class BlockTest extends TestCase
{
    private Block $sut;

    protected function setUp(): void
    {
        $this->sut = new Block();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testItsCodeIsMutable(): void
    {
        $this->sut->setCode('Awesome_Code');
        $this->assertSame('Awesome_Code', $this->sut->getCode());
    }

    public function testItHasNoTitleByDefault(): void
    {
        $this->assertNull($this->sut->getTitle());
    }

    public function testItsTitleIsMutable(): void
    {
        $this->sut->setTitle('Awesome title');
        $this->assertSame('Awesome title', $this->sut->getTitle());
    }

    public function testItHasNoBodyByDefault(): void
    {
        $this->assertNull($this->sut->getBody());
    }

    public function testItsBodyIsMutable(): void
    {
        $this->sut->setBody('<p>Subject body</p>');
        $this->assertSame('<p>Subject body</p>', $this->sut->getBody());
    }

    public function testItHasNoImagePositionByDefault(): void
    {
        $this->assertNull($this->sut->getImagePosition());
    }

    public function testItsImagePositionIsMutable(): void
    {
        $this->sut->setImagePosition(Block::POSITION_LEFT);
        $this->assertSame(Block::POSITION_LEFT, $this->sut->getImagePosition());
    }

    public function testItHasNoClassByDefault(): void
    {
        $this->assertNull($this->sut->getClass());
    }

    public function testItsClassIsMutable(): void
    {
        $this->sut->setClass("well");
        $this->assertSame("well", $this->sut->getClass());
    }

    public function testItHasNoImageByDefault(): void
    {
        $this->assertNull($this->sut->getImage());
    }

    public function testItsImageIsMutable(): void
    {
        $image = $this->createStub(BlockImage::class);

        $this->sut->setImage($image);
        $this->assertSame($image, $this->sut->getImage());
    }

    public function testItHasNoPositionByDefault(): void
    {
        $this->assertNull($this->sut->getPosition());
    }

    public function testItsPositionIsMutable(): void
    {
        $this->sut->setPosition(42);
        $this->assertSame(42, $this->sut->getPosition());
    }

    public function testItHasNoArticleByDefault(): void
    {
        $this->assertNull($this->sut->getArticle());
    }

    public function testItsArticleIsMutable(): void
    {
        $article = $this->createStub(Article::class);

        $this->sut->setArticle($article);
        $this->assertSame($article, $this->sut->getArticle());
    }

    public function testItHasNoSlideShowBlockByDefault(): void
    {
        $this->assertNull($this->sut->getSlideShowBlock());
    }

    public function testItsSlideShowBlockIsMutable(): void
    {
        $slideShowBlock = $this->createStub(SlideShowBlock::class);

        $this->sut->setSlideShowBlock($slideShowBlock);
        $this->assertSame($slideShowBlock, $this->sut->getSlideShowBlock());
    }

    public function testItHasNoProductByDefault(): void
    {
        $this->assertNull($this->sut->getProduct());
    }

    public function testItsProductIsMutable(): void
    {
        $product = $this->createStub(ProductInterface::class);

        $this->sut->setProduct($product);
        $this->assertSame($product, $this->sut->getProduct());
    }
}
