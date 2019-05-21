<?php

namespace spec\App\NotificationManager;

use App\Entity\Customer;
use App\Entity\Notification;
use App\Entity\ProductBox;
use App\Entity\ProductInterface;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductBoxNotificationManagerSpec extends ObjectBehavior
{
    function let(
        UserRepository $userRepository,
        NotificationFactory $notificationFactory,
        RouterInterface $router,
        TranslatorInterface $translator,
        ObjectManager $manager
    ) {
        $this->beConstructedWith($userRepository, $notificationFactory, $router, $translator, $manager);
    }

    function it_create_notifications_for_moderators(
        NotificationFactory $notificationFactory,
        ObjectManager $manager,
        UserRepository $userRepository,
        User $user,
        Customer $customer,
        ProductInterface $product,
        ProductBox $productBox,
        Notification $notification,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $userRepository->findByRole('ROLE_MODERATOR')->willReturn([$user]);
        $user->getCustomer()->willReturn($customer);
        $productBox->getId()->willReturn(42);
        $productBox->getProduct()->willReturn($product);
        $product->getName()->willReturn('Awesome product');

        $translator->trans('text.notification.product_box.ask_for_review',
            ['%PRODUCT_NAME%' => 'Awesome product']
        )->willReturn('new product box to review');

        $notificationFactory->createForProductBox($productBox, $customer)->willReturn($notification);

        $router->generate('app_backend_product_box_update', [
            'id' => 42,
        ])->willReturn('/target');

        $notification->setTarget('/target')->shouldBeCalled();
        $notification->setMessage('new product box to review')->shouldBeCalled();
        $manager->persist($notification)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->notifyModerators($productBox);
    }

    function it_create_notifications_for_author_when_boxes_are_accepted(
        NotificationFactory $notificationFactory,
        ObjectManager $manager,
        Customer $customer,
        User $user,
        ProductInterface $product,
        ProductBox $productBox,
        Notification $notification,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $productBox->getAuthor()->willReturn($customer);
        $productBox->getProduct()->willReturn($product);
        $productBox->getStatus()->willReturn(ProductBox::STATUS_ACCEPTED);
        $customer->getUser()->willReturn($user);
        $user->getCustomer()->willReturn($customer);
        $product->getName()->willReturn('Awesome product');

        $translator->trans('text.notification.product_box.accepted',
            ['%PRODUCT_NAME%' => 'Awesome product']
        )->willReturn('Your product box has been accepted.');

        $notificationFactory->createForProductBox($productBox, $customer)->willReturn($notification);

        $router->generate('app_frontend_account_games_library')->willReturn('/target');

        $notification->setTarget('/target')->shouldBeCalled();
        $notification->setMessage('Your product box has been accepted.')->shouldBeCalled();
        $manager->persist($notification)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->notifyAuthor($productBox);
    }

    function it_does_nothing_when_notifying_author_of_a_box_with_no_author(
        ProductInterface $product,
        ProductBox $productBox,
        TranslatorInterface $translator
    ) {
        $productBox->getAuthor()->willReturn(null);
        $productBox->getProduct()->willReturn($product);
        $productBox->getStatus()->willReturn(ProductBox::STATUS_ACCEPTED);
        $product->getName()->willReturn('Awesome product');

        $translator->trans('text.notification.product_box.accepted',
            ['%PRODUCT_NAME%' => 'Awesome product']
        )->shouldNotBeCalled();

        $this->notifyAuthor($productBox);
    }

    function it_does_nothing_when_notifying_author_of_a_box_with_no_user_author(
        ProductInterface $product,
        ProductBox $productBox,
        Customer $author,
        TranslatorInterface $translator
    ) {
        $productBox->getAuthor()->willReturn($author);
        $productBox->getProduct()->willReturn($product);
        $productBox->getStatus()->willReturn(ProductBox::STATUS_ACCEPTED);
        $product->getName()->willReturn('Awesome product');

        $translator->trans('text.notification.product_box.accepted',
            ['%PRODUCT_NAME%' => 'Awesome product']
        )->shouldNotBeCalled();

        $this->notifyAuthor($productBox);
    }
}
