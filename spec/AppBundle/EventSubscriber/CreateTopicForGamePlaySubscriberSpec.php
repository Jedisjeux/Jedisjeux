<?php

namespace spec\AppBundle\EventSubscriber;

use AppBundle\Entity\Article;
use AppBundle\Entity\GamePlay;
use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use AppBundle\EventSubscriber\CreateTopicForArticleSubscriber;
use AppBundle\EventSubscriber\CreateTopicForGamePlaySubscriber;
use AppBundle\Factory\TopicFactory;
use AppBundle\Repository\TopicRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class CreateTopicForGamePlaySubscriberSpec extends ObjectBehavior
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
        $this->shouldHaveType(CreateTopicForGamePlaySubscriber::class);
    }

    function it_creates_topic_for_game_play(
        GenericEvent $event,
        Post $post,
        Topic $topic,
        GamePlay $gamePlay,
        TopicRepository $topicRepository,
        TopicFactory $topicFactory,
        CustomerInterface $author
    ): void
    {
        $event->getSubject()->willReturn($post);
        $post->getGamePlay()->willReturn($gamePlay);
        $topicRepository->findOneByGamePlay($gamePlay)->willReturn(null);
        $topicFactory->createForGamePlay($gamePlay)->willReturn($topic);
        $post->getAuthor()->willReturn($author);

        $topicFactory->createForGamePlay($gamePlay)->shouldBeCalled();

        $this->onCreate($event);
    }

    function it_does_not_create_new_topic_for_game_play_if_exist(
        GenericEvent $event,
        Post $post,
        Topic $topic,
        GamePlay $gamePlay,
        TopicRepository $topicRepository,
        TopicFactory $topicFactory,
        CustomerInterface $author
    ): void
    {
        $event->getSubject()->willReturn($post);
        $post->getGamePlay()->willReturn($gamePlay);
        $topicRepository->findOneByGamePlay($gamePlay)->willReturn($topic);
        $post->getAuthor()->willReturn($author);

        $topicFactory->createForGamePlay($gamePlay)->shouldNotBeCalled();

        $this->onCreate($event);
    }
}
