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

namespace App\Tests\Unit\Factory;

use App\Entity\Article;
use App\Entity\GamePlay;
use App\Entity\Post;
use App\Entity\Topic;
use App\Factory\PostFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Context\CustomerContextInterface;

final class PostFactoryTest extends TestCase
{
    private PostFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new PostFactory(Post::class, $this->createStub(CustomerContextInterface::class));
    }

    public function testItCanCreateAPost(): void
    {
        $this->assertInstanceOf(Post::class, $this->factory->createNew());
    }

    public function testItCanCreateAPostForATopic(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->expects($this->once())->method('hasPost')->with($this->isInstanceOf(Post::class));
        $topic->expects($this->once())->method('addPost')->with($this->isInstanceOf(Post::class));

        $post = $this->factory->createForTopic($topic);

        $this->assertSame($topic, $post->getTopic());
    }

    public function testItCanCreateAPostForAGamePlay(): void
    {
        $gamePlay = $this->createStub(GamePlay::class);

        $this->assertSame($gamePlay, $this->factory->createForGamePlay($gamePlay)->getGamePlay());
    }

    public function testItCanCreateAPostForAnArticle(): void
    {
        $article = $this->createStub(Article::class);

        $this->assertSame($article, $this->factory->createForArticle($article)->getArticle());
    }
}
