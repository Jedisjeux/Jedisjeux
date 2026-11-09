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
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\NotificationManager\ProductNotificationManager;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProductNotificationManagerTest extends TestCase
{
    public function testItCreatesNotificationsForTranslators(): void
    {
        $product = $this->createProduct();
        $manager = $this->createManager($product, 'ROLE_TRANSLATOR', 'text.notification.product.ask_for_translation', 'new product to translate', $notification);

        $notification->expects($this->once())->method('setTarget')->with('/target');
        $notification->expects($this->once())->method('setMessage')->with('new product to translate');

        $manager->notifyTranslators($product);
    }

    public function testItCreatesNotificationsForReviewers(): void
    {
        $product = $this->createProduct();
        $manager = $this->createManager($product, 'ROLE_REVIEWER', 'text.notification.product.ask_for_review', 'new product to review', $notification);

        $notification->expects($this->once())->method('setTarget')->with('/target');
        $notification->expects($this->once())->method('setMessage')->with('new product to review');

        $manager->notifyReviewers($product);
    }

    public function testItCreatesNotificationsForPublishers(): void
    {
        $product = $this->createProduct();
        $manager = $this->createManager($product, 'ROLE_PUBLISHER', 'text.notification.product.ask_for_publication', 'new product to publish', $notification);

        $notification->expects($this->once())->method('setTarget')->with('/target');
        $notification->expects($this->once())->method('setMessage')->with('new product to publish');

        $manager->notifyPublishers($product);
    }

    private function createProduct(): ProductInterface
    {
        $product = $this->createStub(ProductInterface::class);
        $product->method('getName')->willReturn('Awesome product');
        $product->method('getSlug')->willReturn('awesome-product');

        return $product;
    }

    private function createManager(
        ProductInterface $product,
        string $role,
        string $translationKey,
        string $message,
        ?Notification &$notification,
    ): ProductNotificationManager {
        $customer = $this->createStub(Customer::class);
        $user = $this->createStub(User::class);
        $user->method('getCustomer')->willReturn($customer);

        $userRepository = $this->createStub(UserRepository::class);
        $userRepository->method('findByRole')->willReturnMap([[$role, [$user]]]);

        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturnMap([
            [$translationKey, ['%PRODUCT_NAME%' => 'Awesome product'], $message],
        ]);

        $notification = $this->createMock(Notification::class);
        $factory = $this->createStub(NotificationFactory::class);
        $factory->method('createForProduct')->willReturnMap([[$product, $customer, $notification]]);

        $router = $this->createStub(RouterInterface::class);
        $router->method('generate')->willReturnMap([
            ['sylius_frontend_product_show', ['slug' => 'awesome-product'], '/target'],
        ]);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())->method('persist')->with($notification);
        $objectManager->expects($this->once())->method('flush');

        return new ProductNotificationManager($factory, $objectManager, $userRepository, $router, $translator);
    }
}
