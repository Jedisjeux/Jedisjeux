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

namespace App\Tests\Unit\EventSubscriber;

use App\AppEvents;
use App\Entity\Article;
use App\Entity\Post;
use App\Entity\Topic;
use App\EventSubscriber\CreateTopicForArticleSubscriber;
use App\Factory\TopicFactory;
use App\Repository\TopicRepository;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CreateTopicForArticleSubscriberTest extends TestCase
{
    private ObjectManager&MockObject $manager;

    private TopicRepository&MockObject $topicRepository;

    private TopicFactory&MockObject $topicFactory;

    private CreateTopicForArticleSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->manager = $this->createMock(ObjectManager::class);
        $this->topicRepository = $this->createMock(TopicRepository::class);
        $this->topicFactory = $this->createMock(TopicFactory::class);
        $this->subscriber = new CreateTopicForArticleSubscriber($this->manager, $this->topicRepository, $this->topicFactory);
    }

    public function testItSubscribesToPostCreateEvent(): void
    {
        $this->assertSame([
            AppEvents::POST_PRE_CREATE => 'onCreate',
        ], CreateTopicForArticleSubscriber::getSubscribedEvents());
    }

    public function testItCreatesTopicForArticle(): void
    {
        $author = $this->createStub(CustomerInterface::class);
        $subject = $this->createStub(Article::class);
        $post = $this->createStub(Post::class);
        $post->method('getArticle')->willReturn($subject);
        $post->method('getAuthor')->willReturn($author);
        $topic = $this->createMock(Topic::class);

        $this->topicRepository->method('findOneByArticle')->with($subject)->willReturn(null);
        $this->topicFactory->expects($this->once())->method('createForArticle')->with($subject)->willReturn($topic);
        $this->manager->expects($this->once())->method('persist')->with($topic);
        $topic->expects($this->once())->method('addPost')->with($post);
        $topic->expects($this->once())->method('addFollower')->with($author);

        $this->subscriber->onCreate(new GenericEvent($post));
    }

    public function testItDoesNotCreateNewTopicForArticleIfExist(): void
    {
        $author = $this->createStub(CustomerInterface::class);
        $subject = $this->createStub(Article::class);
        $post = $this->createStub(Post::class);
        $post->method('getArticle')->willReturn($subject);
        $post->method('getAuthor')->willReturn($author);
        $topic = $this->createStub(Topic::class);

        $this->topicRepository->method('findOneByArticle')->with($subject)->willReturn($topic);
        $this->topicFactory->expects($this->never())->method('createForArticle');

        $this->subscriber->onCreate(new GenericEvent($post));
    }

    public function testItDoesNothingIfItIsNotAArticlePost(): void
    {
        $post = $this->createStub(Post::class);
        $post->method('getArticle')->willReturn(null);

        $this->topicFactory->expects($this->never())->method('createForArticle');

        $this->subscriber->onCreate(new GenericEvent($post));
    }
}
