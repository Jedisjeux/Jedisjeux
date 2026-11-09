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
use App\Entity\GamePlay;
use App\Entity\Post;
use App\Entity\Topic;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class TopicTest extends TestCase
{
    private Topic $topic;

    protected function setUp(): void
    {
        $this->topic = new Topic();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->topic);
    }

    public function testItHasNoIdByDefault(): void
    {
        $this->assertNull($this->topic->getId());
    }

    public function testItInitializesACodeByDefault(): void
    {
        $this->assertNotNull($this->topic->getCode());
    }

    public function testItsCodeIsMutable(): void
    {
        $this->topic->setCode('XYZ');

        $this->assertSame('XYZ', $this->topic->getCode());
    }

    public function testItHasNoTitleByDefault(): void
    {
        $this->assertNull($this->topic->getTitle());
    }

    public function testItsTitleIsMutable(): void
    {
        $this->topic->setTitle('Subject title');

        $this->assertSame('Subject title', $this->topic->getTitle());
    }

    public function testItsPostCountIsEqualToZeroByDefault(): void
    {
        $this->assertSame(0, $this->topic->getPostCount());
    }

    public function testItSetsPostCount(): void
    {
        $this->topic->setPostCount(7);

        $this->assertSame(7, $this->topic->getPostCount());
    }

    public function testItHasZeroViewCountByDefault(): void
    {
        $this->assertSame(0, $this->topic->getViewCount());
    }

    public function testItsViewCountIsMutable(): void
    {
        $this->topic->setViewCount(42);

        $this->assertSame(42, $this->topic->getViewCount());
    }

    public function testItHasNoLastPostCreationDateByDefault(): void
    {
        $this->assertNull($this->topic->getLastPostCreatedAt());
    }

    public function testItsLastPostCreationDateByDefault(): void
    {
        $lastPostCreatedAt = new \DateTime();

        $this->topic->setLastPostCreatedAt($lastPostCreatedAt);

        $this->assertSame($lastPostCreatedAt, $this->topic->getLastPostCreatedAt());
    }

    public function testItHaNoFirstPostByDefault(): void
    {
        $this->assertNull($this->topic->getFirstPost());
    }

    public function testItCanGetFirstPost(): void
    {
        $firstPost = $this->createStub(Post::class);
        $lastPost = $this->createStub(Post::class);

        $this->topic->addPost($firstPost);
        $this->topic->addPost($lastPost);

        $this->assertSame($firstPost, $this->topic->getFirstPost());
    }

    public function testItHaNoLastPostByDefault(): void
    {
        $this->assertNull($this->topic->getLastPost());
    }

    public function testItCanGetLastPost(): void
    {
        $firstPost = $this->createStub(Post::class);
        $lastPost = $this->createStub(Post::class);

        $this->topic->addPost($firstPost);
        $this->topic->addPost($lastPost);

        $this->assertSame($lastPost, $this->topic->getLastPost());
    }

    public function testItsAuthorIsMutable(): void
    {
        $author = $this->createStub(CustomerInterface::class);

        $this->topic->setAuthor($author);

        $this->assertSame($author, $this->topic->getAuthor());
    }

    public function testItsMainPostIsMutable(): void
    {
        $mainPost = $this->createStub(Post::class);

        $this->topic->setMainPost($mainPost);

        $this->assertSame($mainPost, $this->topic->getMainPost());
    }

    public function testItsMainTaxonIsMutable(): void
    {
        $mainTaxon = $this->createStub(TaxonInterface::class);

        $this->topic->setMainTaxon($mainTaxon);

        $this->assertSame($mainTaxon, $this->topic->getMainTaxon());
    }

    public function testItsArticleIsMutable(): void
    {
        $article = $this->createStub(Article::class);

        $this->topic->setArticle($article);

        $this->assertSame($article, $this->topic->getArticle());
    }

    public function testItsGamePlayIsMutable(): void
    {
        $gamePlay = $this->createStub(GamePlay::class);

        $this->topic->setGamePlay($gamePlay);

        $this->assertSame($gamePlay, $this->topic->getGamePlay());
    }

    public function testItInitializesPostsCollectionByDefault(): void
    {
        $this->assertInstanceOf(ArrayCollection::class, $this->topic->getPosts());
    }

    public function testItAddsPosts(): void
    {
        $post = $this->createMock(Post::class);
        $post->expects($this->once())->method('setTopic')->with($this->topic);

        $this->topic->addPost($post);

        $this->assertTrue($this->topic->hasPost($post));
    }

    public function testItRemovePosts(): void
    {
        $topics = [];
        $post = $this->createMock(Post::class);
        $post->expects($this->exactly(2))->method('setTopic')->willReturnCallback(
            static function (?Topic $topic) use (&$topics): void {
                $topics[] = $topic;
            },
        );

        $this->topic->addPost($post);
        $this->topic->removePost($post);

        $this->assertFalse($this->topic->hasPost($post));
        $this->assertSame([$this->topic, null], $topics);
    }

    public function testItInitializesFollowersCollectionByDefault(): void
    {
        $this->assertInstanceOf(ArrayCollection::class, $this->topic->getFollowers());
    }

    public function testItAddFollowers(): void
    {
        $follower = $this->createStub(CustomerInterface::class);

        $this->topic->addFollower($follower);

        $this->assertTrue($this->topic->hasFollower($follower));
    }

    public function testItRemoveFollowers(): void
    {
        $follower = $this->createStub(CustomerInterface::class);

        $this->topic->addFollower($follower);
        $this->topic->removeFollower($follower);

        $this->assertFalse($this->topic->hasFollower($follower));
    }

    public function testItHasNoLastPageNumberByDefault(): void
    {
        $this->assertNull($this->topic->getLastPageNumber());
    }

    public function testItHasNoLastPageNumberWhenTopicHasTenPosts(): void
    {
        $this->topic->setPostCount(10);

        $this->assertNull($this->topic->getLastPageNumber());
    }

    public function testItsLastPageNumberCanEqualsToOneWhenTopicHasTenPosts(): void
    {
        $this->topic->setPostCount(10);

        $this->assertSame(1, $this->topic->getLastPageNumber(false));
    }

    public function testItsLastPageNumberEqualsToTwoWhenTopicHasElevenPosts(): void
    {
        $this->topic->setPostCount(11);

        $this->assertSame(2, $this->topic->getLastPageNumber());
    }

    public function testItsStringConversionReturnsTitle(): void
    {
        $this->topic->setTitle('u-topic');

        $this->assertSame('u-topic', (string) $this->topic);
    }
}
