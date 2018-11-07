<?php

namespace spec\App\EventSubscriber;

use App\AppEvents;
use App\Entity\Notification;
use App\Entity\Post;
use App\Entity\Topic;
use App\EventSubscriber\CreateTopicNotificationSubscriber;
use App\Factory\NotificationFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Translation\TranslatorInterface;

class CreateTopicNotificationSubscriberSpec extends ObjectBehavior
{
    function let(
        CustomerContextInterface $customerContext,
        NotificationFactory $factory,
        ObjectManager $manager,
        EntityRepository $repository,
        TranslatorInterface $translator
    ) {
        $this->beConstructedWith($customerContext, $factory, $manager, $repository, $translator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CreateTopicNotificationSubscriber::class);
    }

    function it_subscribes_to_post_create_event()
    {
        $this::getSubscribedEvents()->shouldReturn([
            AppEvents::POST_POST_CREATE => 'onPostCreate',
        ]);
    }

    function it_creates_notifications_for_all_subject_followers(
        GenericEvent $event,
        Post $post,
        Topic $topic,
        Notification $notification,
        CustomerInterface $currentCustomer,
        CustomerInterface $follower,
        CustomerContextInterface $customerContext,
        EntityRepository $repository,
        NotificationFactory $factory,
        TranslatorInterface $translator
    ) {
        $event->getSubject()->willReturn($post);
        $post->getTopic()->willReturn($topic);
        $post->getAuthor()->willReturn($currentCustomer);
        $topic->getTitle()->willReturn('Topic title');
        $topic->getFollowers()->willReturn(new ArrayCollection([$follower->getWrappedObject()]));
        $customerContext->getCustomer()->willReturn($currentCustomer);
        $repository->findOneBy(Argument::type('array'))->willReturn(null);
        $factory->createForPost($post, $follower)->willReturn($notification);
        $translator->trans(Argument::type('string'), Argument::type('array'))->willReturn('message');

        $factory->createForPost($post, $follower)->shouldBeCalled();
        $notification->addAuthor($currentCustomer)->shouldBeCalled();
        $notification->getAuthors()->shouldBeCalled();
        $notification->setMessage('message')->shouldBeCalled();

        $this->onPostCreate($event);
    }

    function it_does_not_create_notification_for_current_customer(
        GenericEvent $event,
        Post $post,
        Topic $topic,
        Notification $notification,
        CustomerInterface $currentCustomer,
        CustomerInterface $follower,
        CustomerContextInterface $customerContext,
        EntityRepository $repository,
        NotificationFactory $factory,
        TranslatorInterface $translator
    ) {
        $event->getSubject()->willReturn($post);
        $post->getTopic()->willReturn($topic);
        $post->getAuthor()->willReturn($currentCustomer);
        $topic->getTitle()->willReturn('Topic title');
        $topic->getFollowers()->willReturn(new ArrayCollection([$follower->getWrappedObject()]));
        $customerContext->getCustomer()->willReturn($currentCustomer);
        $repository->findOneBy(Argument::type('array'))->willReturn(null);
        $factory->createForPost($post, $follower)->willReturn($notification);
        $translator->trans(Argument::type('string'), Argument::type('array'))->willReturn('message');

        $factory->createForPost($post, $currentCustomer)->shouldNotBeCalled();
        $notification->addAuthor($currentCustomer)->shouldBeCalled();
        $notification->getAuthors()->shouldBeCalled();
        $notification->setMessage('message')->shouldBeCalled();

        $this->onPostCreate($event);
    }
}
