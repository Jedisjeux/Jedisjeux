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

use App\Entity\Article;
use App\Entity\Customer;
use App\Entity\Notification;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\NotificationManager\ArticleNotificationManager;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ArticleNotificationManagerTest extends TestCase
{
    public function testItCreatesNotificationsForReviewers(): void
    {
        $article = $this->createArticle();
        $manager = $this->createManager($article, 'ROLE_REVIEWER', 'text.notification.article.ask_for_review', 'new article to review', $notification);

        $notification->expects($this->once())->method('setTarget')->with('/target');
        $notification->expects($this->once())->method('setMessage')->with('new article to review');

        $manager->notifyReviewers($article);
    }

    public function testItCreatesNotificationsForPublishers(): void
    {
        $article = $this->createArticle();
        $manager = $this->createManager($article, 'ROLE_PUBLISHER', 'text.notification.article.ask_for_publication', 'new article to publish', $notification);

        $notification->expects($this->once())->method('setTarget')->with('/target');
        $notification->expects($this->once())->method('setMessage')->with('new article to publish');

        $manager->notifyPublishers($article);
    }

    private function createArticle(): Article
    {
        $article = $this->createStub(Article::class);
        $article->method('getName')->willReturn('Awesome article');
        $article->method('getSlug')->willReturn('awesome-article');

        return $article;
    }

    private function createManager(
        Article $article,
        string $role,
        string $translationKey,
        string $message,
        ?Notification &$notification,
    ): ArticleNotificationManager {
        $customer = $this->createStub(Customer::class);
        $user = $this->createStub(User::class);
        $user->method('getCustomer')->willReturn($customer);

        $userRepository = $this->createStub(UserRepository::class);
        $userRepository->method('findByRole')->willReturnMap([[$role, [$user]]]);

        $translator = $this->createStub(TranslatorInterface::class);
        $translator->method('trans')->willReturnMap([
            [$translationKey, ['%ARTICLE_NAME%' => 'Awesome article'], $message],
        ]);

        $notification = $this->createMock(Notification::class);
        $factory = $this->createStub(NotificationFactory::class);
        $factory->method('createForArticle')->willReturnMap([[$article, $customer, $notification]]);

        $router = $this->createStub(RouterInterface::class);
        $router->method('generate')->willReturnMap([
            ['app_frontend_article_show', ['slug' => 'awesome-article'], '/target'],
        ]);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())->method('persist')->with($notification);
        $objectManager->expects($this->once())->method('flush');

        return new ArticleNotificationManager($factory, $objectManager, $userRepository, $router, $translator);
    }
}
