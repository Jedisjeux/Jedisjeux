<?php

namespace spec\App\NotificationManager;

use App\Entity\Article;
use App\Entity\Customer;
use App\Entity\Notification;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ArticleNotificationManagerSpec extends ObjectBehavior
{
    function let(
        NotificationFactory $factory,
        ObjectManager $manager,
        UserRepository $userRepository,
        RouterInterface $router,
        TranslatorInterface $translator
    ) {
        $this->beConstructedWith($factory, $manager, $userRepository, $router, $translator);
    }

    function it_create_notifications_for_reviewers(
        NotificationFactory $factory,
        ObjectManager $manager,
        UserRepository $userRepository,
        User $user,
        Customer $customer,
        Article $article,
        Notification $notification,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $userRepository->findByRole('ROLE_REVIEWER')->willReturn([$user]);
        $user->getCustomer()->willReturn($customer);
        $article->getName()->willReturn('Awesome article');
        $article->getSlug()->willReturn('awesome-article');

        $translator->trans('text.notification.article.ask_for_review',
            ['%ARTICLE_NAME%' => 'Awesome article']
        )->willReturn('new article to review');

        $factory->createForArticle($article, $customer)->willReturn($notification);

        $router->generate('app_frontend_article_show', [
                'slug' => 'awesome-article',
        ])->willReturn('/target');

        $notification->setTarget('/target')->shouldBeCalled();
        $notification->setMessage('new article to review')->shouldBeCalled();
        $manager->persist($notification)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->notifyReviewers($article);
    }

    function it_create_notifications_for_publishers(
        NotificationFactory $factory,
        ObjectManager $manager,
        UserRepository $userRepository,
        User $user,
        Customer $customer,
        Article $article,
        Notification $notification,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $userRepository->findByRole('ROLE_PUBLISHER')->willReturn([$user]);
        $user->getCustomer()->willReturn($customer);
        $article->getName()->willReturn('Awesome article');
        $article->getSlug()->willReturn('awesome-article');

        $translator->trans('text.notification.article.ask_for_publication',
            ['%ARTICLE_NAME%' => 'Awesome article']
        )->willReturn('new article to publish');

        $factory->createForArticle($article, $customer)->willReturn($notification);

        $router->generate('app_frontend_article_show', [
            'slug' => 'awesome-article',
        ])->willReturn('/target');

        $notification->setTarget('/target')->shouldBeCalled();
        $notification->setMessage('new article to publish')->shouldBeCalled();
        $manager->persist($notification)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->notifyPublishers($article);
    }
}
