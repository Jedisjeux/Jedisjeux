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
use App\Entity\ProductFile;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\NotificationManager\ProductFileNotificationManager;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProductFileNotificationManagerTest extends TestCase
{
    public function testItCreatesNotificationsForReviewers(): void
    {
        $customer = $this->createStub(Customer::class);
        $user = $this->createStub(User::class);
        $user->method('getCustomer')->willReturn($customer);
        $productFile = $this->createProductFile();
        $notification = $this->createMock(Notification::class);

        $userRepository = $this->createStub(UserRepository::class);
        $userRepository->method('findByRole')->willReturnMap([['ROLE_MODERATOR', [$user]]]);
        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturnMap([
            ['text.notification.product_file.ask_for_review', ['%title%' => 'Awesome file'], 'new product file to review'],
        ]);
        $factory = $this->createStub(NotificationFactory::class);
        $factory->method('createForProductFile')->willReturnMap([[$productFile, $customer, $notification]]);
        $router = $this->createStub(RouterInterface::class);
        $router->method('generate')->willReturnMap([['app_backend_product_file_update', ['id' => 42], '/target']]);
        $objectManager = $this->createMock(ObjectManager::class);

        $notification->expects($this->once())->method('setTarget')->with('/target');
        $notification->expects($this->once())->method('setMessage')->with('new product file to review');
        $objectManager->expects($this->once())->method('persist')->with($notification);
        $objectManager->expects($this->once())->method('flush');

        $manager = new ProductFileNotificationManager($userRepository, $factory, $router, $translator, $objectManager);
        $manager->notifyModerators($productFile);
    }

    public function testItCreatesNotificationsForAuthorWhenBoxesAreAccepted(): void
    {
        $customer = $this->createStub(Customer::class);
        $user = $this->createStub(User::class);
        $user->method('getCustomer')->willReturn($customer);
        $customer->method('getUser')->willReturn($user);
        $productFile = $this->createProductFile($customer);
        $notification = $this->createMock(Notification::class);

        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturnMap([
            ['text.notification.product_file.accepted', ['%title%' => 'Awesome file'], 'Your file has been accepted.'],
        ]);
        $factory = $this->createStub(NotificationFactory::class);
        $factory->method('createForProductFile')->willReturnMap([[$productFile, $customer, $notification]]);
        $router = $this->createStub(RouterInterface::class);
        $router->method('generate')->willReturnMap([['app_frontend_account_games_library', '/target']]);
        $objectManager = $this->createMock(ObjectManager::class);

        $notification->expects($this->once())->method('setTarget')->with('/target');
        $notification->expects($this->once())->method('setMessage')->with('Your file has been accepted.');
        $objectManager->expects($this->once())->method('persist')->with($notification);
        $objectManager->expects($this->once())->method('flush');

        $manager = new ProductFileNotificationManager($this->createStub(UserRepository::class), $factory, $router, $translator, $objectManager);
        $manager->notifyAuthor($productFile);
    }

    public function testItDoesNothingWhenNotifyingAuthorOfABoxWithNoAuthor(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);

        $translator->expects($this->never())->method('trans');

        $this->createManagerWithTranslator($translator)->notifyAuthor($this->createProductFile());
    }

    public function testItDoesNothingWhenNotifyingAuthorOfABoxWithNoUserAuthor(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);

        $translator->expects($this->never())->method('trans');

        $this->createManagerWithTranslator($translator)->notifyAuthor($this->createProductFile($this->createStub(Customer::class)));
    }

    private function createManagerWithTranslator(TranslatorInterface $translator): ProductFileNotificationManager
    {
        return new ProductFileNotificationManager(
            $this->createStub(UserRepository::class),
            $this->createStub(NotificationFactory::class),
            $this->createStub(RouterInterface::class),
            $translator,
            $this->createStub(ObjectManager::class),
        );
    }

    private function createProductFile(?Customer $author = null): ProductFile
    {
        $productFile = $this->createStub(ProductFile::class);
        $productFile->method('getId')->willReturn(42);
        $productFile->method('getTitle')->willReturn('Awesome file');
        $productFile->method('getAuthor')->willReturn($author);
        $productFile->method('getStatus')->willReturn(ProductFile::STATUS_ACCEPTED);

        return $productFile;
    }
}
