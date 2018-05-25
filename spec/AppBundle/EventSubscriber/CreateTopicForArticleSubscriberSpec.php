<?php

namespace spec\AppBundle\EventSubscriber;

use AppBundle\Entity\Article;
use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use AppBundle\EventSubscriber\CreateTopicForArticleSubscriber;
use AppBundle\Factory\TopicFactory;
use AppBundle\Repository\TopicRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class CreateTopicForArticleSubscriberSpec extends ObjectBehavior
{
    function let(
        ObjectManager $manager,
        TopicRepository $topicRepository,
        TopicFactory $topicFactory
    ) {
        $this->beConstructedWith($manager, $topicRepository, $topicFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CreateTopicForArticleSubscriber::class);
    }

    function it_creates_topic_for_article(
        GenericEvent $event,
        Post $post,
        Topic $topic,
        Article $article,
        TopicRepository $topicRepository,
        TopicFactory $topicFactory,
        CustomerInterface $author
    ): void
    {
        $event->getSubject()->willReturn($post);
        $post->getArticle()->willReturn($article);
        $topicRepository->findOneByArticle($article)->willReturn(null);
        $topicFactory->createForArticle($article)->willReturn($topic);
        $post->getAuthor()->willReturn($author);

        $topicFactory->createForArticle($article)->shouldBeCalled();

        $this->onCreate($event);
    }

    function it_does_not_create_new_topic_for_article_if_exist(
        GenericEvent $event,
        Post $post,
        Topic $topic,
        Article $article,
        TopicRepository $topicRepository,
        TopicFactory $topicFactory,
        CustomerInterface $author
    ): void
    {
        $event->getSubject()->willReturn($post);
        $post->getArticle()->willReturn($article);
        $topicRepository->findOneByArticle($article)->willReturn($topic);
        $post->getAuthor()->willReturn($author);

        $topicFactory->createForArticle($article)->shouldNotBeCalled();

        $this->onCreate($event);
    }
}
