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

use App\Entity\CustomerInterface;
use App\Entity\File;
use App\Entity\ProductFile;
use App\Entity\ProductInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\ResourceInterface;

final class ProductFileTest extends TestCase
{
    private ProductFile $file;

    protected function setUp(): void
    {
        $this->file = new ProductFile();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->file);
    }

    public function testItExtendsAFileModel(): void
    {
        $this->assertInstanceOf(File::class, $this->file);
    }

    public function testItHasNoIdByDefault(): void
    {
        $this->assertNull($this->file->getId());
    }

    public function testItInitializesACodeByDefault(): void
    {
        $this->assertNotNull($this->file->getCode());
    }

    public function testItsCodeIsMutable(): void
    {
        $this->file->setCode('X-files');

        $this->assertSame('X-files', $this->file->getCode());
    }

    public function testItHasNoProductByDefault(): void
    {
        $this->assertNull($this->file->getProduct());
    }

    public function testItsProductIsMutable(): void
    {
        $product = $this->createStub(ProductInterface::class);

        $this->file->setProduct($product);

        $this->assertSame($product, $this->file->getProduct());
    }

    public function testItHasNoAuthorByDefault(): void
    {
        $this->assertNull($this->file->getAuthor());
    }

    public function testItsAuthorIsMutable(): void
    {
        $author = $this->createStub(CustomerInterface::class);

        $this->file->setAuthor($author);

        $this->assertSame($author, $this->file->getAuthor());
    }

    public function testItHasNoFileByDefault(): void
    {
        $this->assertNull($this->file->getFile());
    }

    public function testItsFileIsMutable(): void
    {
        $splFile = new \SplFileInfo(__FILE__);

        $this->file->setFile($splFile);

        $this->assertSame($splFile, $this->file->getFile());
        $this->assertNotNull($this->file->getUpdatedAt());
    }

    public function testItHasNoTitleByDefault(): void
    {
        $this->assertNull($this->file->getTitle());
    }

    public function testItsTitleIsMutable(): void
    {
        $this->file->setTitle('Awesome file');

        $this->assertSame('Awesome file', $this->file->getTitle());
    }

    public function testItHasNoPathByDefault(): void
    {
        $this->assertNull($this->file->getPath());
    }

    public function testItsPathIsMutable(): void
    {
        $this->file->setPath('/path/to/file.txt');

        $this->assertSame('/path/to/file.txt', $this->file->getPath());
    }

    public function testItIsNewByDefault(): void
    {
        $this->assertSame(ProductFile::STATUS_NEW, $this->file->getStatus());
    }

    public function testItCanBeAccepted(): void
    {
        $this->file->setStatus(ProductFile::STATUS_ACCEPTED);

        $this->assertSame(ProductFile::STATUS_ACCEPTED, $this->file->getStatus());
    }

    public function testItCanBeRejected(): void
    {
        $this->file->setStatus(ProductFile::STATUS_REJECTED);

        $this->assertSame(ProductFile::STATUS_REJECTED, $this->file->getStatus());
    }

    public function testItHasNoCreationDateByDefault(): void
    {
        $this->assertNull($this->file->getCreatedAt());
    }

    public function testItsCreationDateIsMutable(): void
    {
        $date = new \DateTime();

        $this->file->setCreatedAt($date);

        $this->assertSame($date, $this->file->getCreatedAt());
    }

    public function testItHasNoUpdateDateByDefault(): void
    {
        $this->assertNull($this->file->getUpdatedAt());
    }

    public function testItsUpdateDateIsMutable(): void
    {
        $date = new \DateTime();

        $this->file->setUpdatedAt($date);

        $this->assertSame($date, $this->file->getUpdatedAt());
    }
}
