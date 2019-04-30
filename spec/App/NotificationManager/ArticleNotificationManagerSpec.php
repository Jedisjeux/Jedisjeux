<?php

namespace spec\App\NotificationManager;

use App\Entity\Article;
use App\Entity\Customer;
use App\Entity\CustomerInterface;
use App\Entity\Notification;
use App\Entity\ProductInterface;
use App\Entity\ProductSubscription;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Repository\CustomerRepository;
use App\Repository\UserRepositoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArticleNotificationManagerSpec extends ObjectBehavior
{
    function let(
        NotificationFactory $factory,
        ObjectManager $manager,
        UserRepositoryInterface $appUserRepository,
        CustomerRepository $customerRepository,
        RouterInterface $router,
        TranslatorInterface $translator
    ) {
        $this->beConstructedWith($factory, $manager, $appUserRepository, $customerRepository, $router, $translator);
    }

    function it_create_notifications_for_reviewers(
        NotificationFactory $factory,
        ObjectManager $manager,
        UserRepositoryInterface $appUserRepository,
        User $user,
        Customer $customer,
        Article $article,
        Notification $notification,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $appUserRepository->findByRole('ROLE_REVIEWER')->willReturn([$user]);
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
        UserRepositoryInterface $appUserRepository,
        User $user,
        Customer $customer,
        Article $article,
        Notification $notification,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $appUserRepository->findByRole('ROLE_PUBLISHER')->willReturn([$user]);
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

    function it_does_not_notify_subscribers_when_article_has_no_product_while(
        Article $article,
        NotificationFactory $factory
    ) {
        $article->getProduct()->willReturn(null);

        $factory->createForArticle($article, Argument::cetera())->shouldNotBeCalled();

        $this->notifySubscribers($article);
    }

    function it_does_not_notify_customers_with_no_subscriptions_to_an_article(
        Article $article,
        ProductInterface $product,
        NotificationFactory $factory,
        CustomerRepository $customerRepository
    ) {
        $article->getProduct()->willReturn($product);
        $customerRepository->findSubscribersToProductForOption($product, ProductSubscription::OPTION_FOLLOW_ARTICLES)->willReturn([]);

        $factory->createForArticle($article, Argument::cetera())->shouldNotBeCalled();

        $this->notifySubscribers($article);
    }

    function it_create_notifications_for_subscribers(
        Article $article,
        ProductInterface $product,
        CustomerRepository $customerRepository,
        CustomerInterface $subscriber,
        NotificationFactory $factory,
        Notification $notification
    ) {
        $article->getProduct()->willReturn($product);
        $article->getSlug()->willReturn('awesome-article');
        $customerRepository->findSubscribersToProductForOption($product, ProductSubscription::OPTION_FOLLOW_ARTICLES)->willReturn([$subscriber]);
        $factory->createForArticle($article, $subscriber)->willReturn($notification);

        $factory->createForArticle($article, $subscriber)->shouldBeCalled();

        $this->notifySubscribers($article);
    }
}
