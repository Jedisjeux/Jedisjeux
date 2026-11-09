<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\Tests\Unit\NotificationManager;

use App\Entity\Customer;
use App\Entity\Notification;
use App\Entity\ProductBox;
use App\Entity\ProductInterface;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\NotificationManager\ProductBoxNotificationManager;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProductBoxNotificationManagerTest extends TestCase
{
    public function testItCreatesNotificationsForModerators(): void
    {
        $customer = $this->createStub(Customer::class);
        $user = $this->createStub(User::class);
        $user->method('getCustomer')->willReturn($customer);
        $productBox = $this->createProductBox();
        $notification = $this->createMock(Notification::class);

        $userRepository = $this->createStub(UserRepository::class);
        $userRepository->method('findByRole')->willReturnMap([['ROLE_MODERATOR', [$user]]]);
        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturnMap([
            ['text.notification.product_box.ask_for_review', ['%PRODUCT_NAME%' => 'Awesome product'], 'new product box to review'],
        ]);
        $factory = $this->createStub(NotificationFactory::class);
        $factory->method('createForProductBox')->willReturnMap([[$productBox, $customer, $notification]]);
        $router = $this->createStub(RouterInterface::class);
        $router->method('generate')->willReturnMap([['app_backend_product_box_update', ['id' => 42], '/target']]);
        $objectManager = $this->createMock(ObjectManager::class);

        $notification->expects($this->once())->method('setTarget')->with('/target');
        $notification->expects($this->once())->method('setMessage')->with('new product box to review');
        $objectManager->expects($this->once())->method('persist')->with($notification);
        $objectManager->expects($this->once())->method('flush');

        $manager = new ProductBoxNotificationManager($userRepository, $factory, $router, $translator, $objectManager);
        $manager->notifyModerators($productBox);
    }

    public function testItCreatesNotificationsForAuthorWhenBoxesAreAccepted(): void
    {
        $customer = $this->createStub(Customer::class);
        $user = $this->createStub(User::class);
        $user->method('getCustomer')->willReturn($customer);
        $customer->method('getUser')->willReturn($user);
        $productBox = $this->createProductBox($customer);
        $notification = $this->createMock(Notification::class);

        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturnMap([
            ['text.notification.product_box.accepted', ['%PRODUCT_NAME%' => 'Awesome product'], 'Your product box has been accepted.'],
        ]);
        $factory = $this->createStub(NotificationFactory::class);
        $factory->method('createForProductBox')->willReturnMap([[$productBox, $customer, $notification]]);
        $router = $this->createStub(RouterInterface::class);
        $router->method('generate')->willReturnMap([['app_frontend_account_games_library', '/target']]);
        $objectManager = $this->createMock(ObjectManager::class);

        $notification->expects($this->once())->method('setTarget')->with('/target');
        $notification->expects($this->once())->method('setMessage')->with('Your product box has been accepted.');
        $objectManager->expects($this->once())->method('persist')->with($notification);
        $objectManager->expects($this->once())->method('flush');

        $manager = new ProductBoxNotificationManager($this->createStub(UserRepository::class), $factory, $router, $translator, $objectManager);
        $manager->notifyAuthor($productBox);
    }

    public function testItDoesNothingWhenNotifyingAuthorOfABoxWithNoAuthor(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);

        $translator->expects($this->never())->method('trans');

        $manager = $this->createManagerWithTranslator($translator);
        $manager->notifyAuthor($this->createProductBox());
    }

    public function testItDoesNothingWhenNotifyingAuthorOfABoxWithNoUserAuthor(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);

        $translator->expects($this->never())->method('trans');

        $manager = $this->createManagerWithTranslator($translator);
        $manager->notifyAuthor($this->createProductBox($this->createStub(Customer::class)));
    }

    private function createManagerWithTranslator(TranslatorInterface $translator): ProductBoxNotificationManager
    {
        return new ProductBoxNotificationManager(
            $this->createStub(UserRepository::class),
            $this->createStub(NotificationFactory::class),
            $this->createStub(RouterInterface::class),
            $translator,
            $this->createStub(ObjectManager::class),
        );
    }

    private function createProductBox(?Customer $author = null): ProductBox
    {
        $product = $this->createStub(ProductInterface::class);
        $product->method('getName')->willReturn('Awesome product');

        $productBox = $this->createStub(ProductBox::class);
        $productBox->method('getId')->willReturn(42);
        $productBox->method('getProduct')->willReturn($product);
        $productBox->method('getAuthor')->willReturn($author);
        $productBox->method('getStatus')->willReturn(ProductBox::STATUS_ACCEPTED);

        return $productBox;
    }
}
