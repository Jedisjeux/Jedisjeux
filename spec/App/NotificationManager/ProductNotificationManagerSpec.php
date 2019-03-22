<?php

namespace spec\App\NotificationManager;

use App\Entity\Customer;
use App\Entity\Notification;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductNotificationManagerSpec extends ObjectBehavior
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

    function it_create_notifications_for_translators(
        NotificationFactory $factory,
        ObjectManager $manager,
        UserRepository $userRepository,
        User $user,
        Customer $customer,
        ProductInterface $product,
        Notification $notification,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $userRepository->findByRole('ROLE_TRANSLATOR')->willReturn([$user]);
        $user->getCustomer()->willReturn($customer);
        $product->getName()->willReturn('Awesome product');
        $product->getSlug()->willReturn('awesome-product');

        $translator->trans('text.notification.product.ask_for_translation',
            ['%PRODUCT_NAME%' => 'Awesome product']
        )->willReturn('new product to translate');

        $factory->createForProduct($product, $customer)->willReturn($notification);

        $router->generate('sylius_frontend_product_show', [
            'slug' => 'awesome-product',
        ])->willReturn('/target');

        $notification->setTarget('/target')->shouldBeCalled();
        $notification->setMessage('new product to translate')->shouldBeCalled();
        $manager->persist($notification)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->notifyTranslators($product);
    }

    function it_create_notifications_for_reviewers(
        NotificationFactory $factory,
        ObjectManager $manager,
        UserRepository $userRepository,
        User $user,
        Customer $customer,
        ProductInterface $product,
        Notification $notification,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $userRepository->findByRole('ROLE_REVIEWER')->willReturn([$user]);
        $user->getCustomer()->willReturn($customer);
        $product->getName()->willReturn('Awesome product');
        $product->getSlug()->willReturn('awesome-product');

        $translator->trans('text.notification.product.ask_for_review',
            ['%PRODUCT_NAME%' => 'Awesome product']
        )->willReturn('new product to review');

        $factory->createForProduct($product, $customer)->willReturn($notification);

        $router->generate('sylius_frontend_product_show', [
            'slug' => 'awesome-product',
        ])->willReturn('/target');

        $notification->setTarget('/target')->shouldBeCalled();
        $notification->setMessage('new product to review')->shouldBeCalled();
        $manager->persist($notification)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->notifyReviewers($product);
    }

    function it_create_notifications_for_publishers(
        NotificationFactory $factory,
        ObjectManager $manager,
        UserRepository $userRepository,
        User $user,
        Customer $customer,
        ProductInterface $product,
        Notification $notification,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $userRepository->findByRole('ROLE_PUBLISHER')->willReturn([$user]);
        $user->getCustomer()->willReturn($customer);
        $product->getName()->willReturn('Awesome product');
        $product->getSlug()->willReturn('awesome-product');

        $translator->trans('text.notification.product.ask_for_publication',
            ['%PRODUCT_NAME%' => 'Awesome product']
        )->willReturn('new product to publish');

        $factory->createForProduct($product, $customer)->willReturn($notification);

        $router->generate('sylius_frontend_product_show', [
            'slug' => 'awesome-product',
        ])->willReturn('/target');

        $notification->setTarget('/target')->shouldBeCalled();
        $notification->setMessage('new product to publish')->shouldBeCalled();
        $manager->persist($notification)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->notifyPublishers($product);
    }
}
