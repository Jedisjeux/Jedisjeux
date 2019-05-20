<?php

namespace spec\App\NotificationManager;

use App\Entity\Customer;
use App\Entity\Notification;
use App\Entity\ProductFile;
use App\Entity\ProductInterface;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductFileNotificationManagerSpec extends ObjectBehavior
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

    function it_create_notifications_for_reviewers(
        NotificationFactory $notificationFactory,
        ObjectManager $manager,
        UserRepository $userRepository,
        User $user,
        Customer $customer,
        ProductFile $productFile,
        Notification $notification,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $userRepository->findByRole('ROLE_REVIEWER')->willReturn([$user]);
        $user->getCustomer()->willReturn($customer);
        $productFile->getId()->willReturn(42);
        $productFile->getTitle()->willReturn('Awesome file');

        $translator->trans('text.notification.product_file.ask_for_review',
            ['%title%' => 'Awesome file']
        )->willReturn('new product file to review');

        $notificationFactory->createForProductFile($productFile, $customer)->willReturn($notification);

        $router->generate('app_backend_product_file_update', [
            'id' => 42,
        ])->willReturn('/target');

        $notification->setTarget('/target')->shouldBeCalled();
        $notification->setMessage('new product file to review')->shouldBeCalled();
        $manager->persist($notification)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->notifyModerators($productFile);
    }

    function it_create_notifications_for_author_when_boxes_are_accepted(
        NotificationFactory $notificationFactory,
        ObjectManager $manager,
        Customer $customer,
        User $user,
        ProductFile $productFile,
        Notification $notification,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $productFile->getAuthor()->willReturn($customer);
        $productFile->getStatus()->willReturn(ProductFile::STATUS_ACCEPTED);
        $customer->getUser()->willReturn($user);
        $user->getCustomer()->willReturn($customer);
        $productFile->getTitle()->willReturn('Awesome file');

        $translator->trans('text.notification.product_file.accepted',
            ['%title%' => 'Awesome file']
        )->willReturn('Your file has been accepted.');

        $notificationFactory->createForProductFile($productFile, $customer)->willReturn($notification);

        $router->generate('app_frontend_account_games_library')->willReturn('/target');

        $notification->setTarget('/target')->shouldBeCalled();
        $notification->setMessage('Your file has been accepted.')->shouldBeCalled();
        $manager->persist($notification)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->notifyAuthor($productFile);
    }

    function it_does_nothing_when_notifying_author_of_a_box_with_no_author(
        ProductFile $productFile,
        TranslatorInterface $translator
    ) {
        $productFile->getAuthor()->willReturn(null);
        $productFile->getStatus()->willReturn(ProductFile::STATUS_ACCEPTED);
        $productFile->getTitle()->willReturn('Awesome file');

        $translator->trans('text.notification.product_file.accepted',
            ['%PRODUCT_NAME%' => 'Awesome product']
        )->shouldNotBeCalled();

        $this->notifyAuthor($productFile);
    }

    function it_does_nothing_when_notifying_author_of_a_box_with_no_user_author(
        ProductFile $productFile,
        Customer $author,
        TranslatorInterface $translator
    ) {
        $productFile->getAuthor()->willReturn($author);
        $productFile->getStatus()->willReturn(ProductFile::STATUS_ACCEPTED);
        $productFile->getTitle()->willReturn('Awesome file');

        $translator->trans('text.notification.product_file.accepted',
            ['%title%' => 'Awesome file']
        )->shouldNotBeCalled();

        $this->notifyAuthor($productFile);
    }
}
