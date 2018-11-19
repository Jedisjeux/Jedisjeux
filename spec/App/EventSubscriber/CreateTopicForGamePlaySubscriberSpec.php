<?php

namespace spec\App\EventSubscriber;

use App\AppEvents;
use App\Entity\GamePlay;
use App\Entity\Post;
use App\Entity\Topic;
use App\EventSubscriber\CreateTopicForGamePlaySubscriber;
use App\Factory\TopicFactory;
use App\Repository\TopicRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
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

    function it_subscribes_to_post_create_event()
    {
        $this::getSubscribedEvents()->shouldReturn([
            AppEvents::POST_PRE_CREATE => 'onCreate',
        ]);
    }

    function it_creates_topic_for_game_play(
        GenericEvent $event,
        Post $post,
        Topic $topic,
        GamePlay $gamePlay,
        TopicRepository $topicRepository,
        TopicFactory $topicFactory,
        CustomerInterface $author,
        ObjectManager $manager
    ): void
    {
        $event->getSubject()->willReturn($post);
        $post->getGamePlay()->willReturn($gamePlay);
        $topicRepository->findOneByGamePlay($gamePlay)->willReturn(null);
        $topicFactory->createForGamePlay($gamePlay)->willReturn($topic);
        $post->getAuthor()->willReturn($author);

        $topicFactory->createForGamePlay($gamePlay)->shouldBeCalled();
        $manager->persist($topic)->shouldBeCalled();
        $topic->addPost($post)->shouldBeCalled();
        $topic->addFollower($author)->shouldBeCalled();

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

    function it_does_nothing_if_it_is_not_a_game_play_post(
        GenericEvent $event,
        Post $post,
        GamePlay $gamePlay,
        TopicFactory $topicFactory
    )
    {
        $event->getSubject()->willReturn($post);
        $post->getGamePlay()->willReturn(null);

        $topicFactory->createForGamePlay($gamePlay)->shouldNotBeCalled();

        $this->onCreate($event);
    }
}
