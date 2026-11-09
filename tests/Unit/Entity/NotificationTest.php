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
use App\Entity\Notification;
use App\Entity\ProductBox;
use App\Entity\ProductFile;
use App\Entity\Topic;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class NotificationTest extends TestCase
{
    private Notification $sut;

    protected function setUp(): void
    {
        $this->sut = new Notification();
    }

    public function testImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testIsNotReadByDefault(): void
    {
        $this->assertFalse($this->sut->isRead());
    }

    public function testCanBeRead(): void
    {
        $this->sut->setRead(true);

        $this->assertTrue($this->sut->isRead());
    }

    public function testHasNoMessageByDefault(): void
    {
        $this->assertSame(null, $this->sut->getMessage());
    }

    public function testItsMessageIsMutable(): void
    {
        $this->sut->setMessage("You should not pass");

        $this->assertSame("You should not pass", $this->sut->getMessage());
    }

    public function testHasNoTargetByDefault(): void
    {
        $this->assertSame(null, $this->sut->getTarget());
    }

    public function testItsTargetIsMutable(): void
    {
        $this->sut->setTarget("http://example.com");

        $this->assertSame("http://example.com", $this->sut->getTarget());
    }

    public function testHasNoRecipientByDefault(): void
    {
        $this->assertSame(null, $this->sut->getRecipient());
    }

    public function testItsRecipientIsMutable(): void
    {
        $obj = $this->createStub(CustomerInterface::class);

        $this->sut->setRecipient($obj);

        $this->assertSame($obj, $this->sut->getRecipient());
    }

    public function testInitializesAuthorsCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->sut->getAuthors());
    }

    public function testAddsAuthor(): void
    {
        $author = $this->createStub(CustomerInterface::class);

        $this->sut->addAuthor($author);

        $this->assertTrue($this->sut->hasAuthor($author));
    }

    public function testRemovesAuthor(): void
    {
        $author = $this->createStub(CustomerInterface::class);

        $this->sut->addAuthor($author);
        $this->sut->removeAuthor($author);

        $this->assertFalse($this->sut->hasAuthor($author));
    }

    public function testHasNoTopicByDefault(): void
    {
        $this->assertSame(null, $this->sut->getTopic());
    }

    public function testItsTopicIsMutable(): void
    {
        $obj = $this->createStub(Topic::class);

        $this->sut->setTopic($obj);

        $this->assertSame($obj, $this->sut->getTopic());
    }

    public function testHasNoProductByDefault(): void
    {
        $this->assertSame(null, $this->sut->getProduct());
    }

    public function testItsProductIsMutable(): void
    {
        $obj = $this->createStub(ProductInterface::class);

        $this->sut->setProduct($obj);

        $this->assertSame($obj, $this->sut->getProduct());
    }

    public function testHasNoArticleByDefault(): void
    {
        $this->assertSame(null, $this->sut->getArticle());
    }

    public function testItsArticleIsMutable(): void
    {
        $obj = $this->createStub(Article::class);

        $this->sut->setArticle($obj);

        $this->assertSame($obj, $this->sut->getArticle());
    }

    public function testHasNoProductBoxByDefault(): void
    {
        $this->assertSame(null, $this->sut->getProductBox());
    }

    public function testItsProductBoxIsMutable(): void
    {
        $obj = $this->createStub(ProductBox::class);

        $this->sut->setProductBox($obj);

        $this->assertSame($obj, $this->sut->getProductBox());
    }

    public function testHasNoProductFileByDefault(): void
    {
        $this->assertSame(null, $this->sut->getProductFile());
    }

    public function testItsProductFileIsMutable(): void
    {
        $obj = $this->createStub(ProductFile::class);

        $this->sut->setProductFile($obj);

        $this->assertSame($obj, $this->sut->getProductFile());
    }
}
