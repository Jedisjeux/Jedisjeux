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

use App\Entity\Post;
use App\Entity\Topic;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class PostTest extends TestCase
{
    private Post $sut;

    protected function setUp(): void
    {
        $this->sut = new Post();
    }

    public function testImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testItsBodyIsMutable(): void
    {
        $this->sut->setBody('<p>Subject body</p>');

        $this->assertSame('<p>Subject body</p>', $this->sut->getBody());
    }

    public function testItsParentIsMutable(): void
    {
        $obj = $this->createStub(Topic::class);

        $this->sut->setParent($obj);

        $this->assertSame($obj, $this->sut->getParent());
    }

    public function testItsTopicIsMutable(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->method('hasPost')->with($this->sut)->willReturn(false);
        $topic->expects($this->once())->method('addPost')->with($this->sut);

        $this->sut->setTopic($topic);

        $this->assertSame($topic, $this->sut->getTopic());
    }

    public function testItsAuthorIsMutable(): void
    {
        $obj = $this->createStub(CustomerInterface::class);

        $this->sut->setAuthor($obj);

        $this->assertSame($obj, $this->sut->getAuthor());
    }
}
