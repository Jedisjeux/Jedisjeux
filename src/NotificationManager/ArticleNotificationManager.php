<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\NotificationManager;

use App\Entity\Article;
use App\Entity\CustomerInterface;
use App\Entity\ProductSubscription;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Repository\CustomerRepository;
use App\Repository\UserRepositoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleNotificationManager
{
    /**
     * @var NotificationFactory
     */
    private $factory;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var UserRepositoryInterface
     */
    private $appUserRepository;

    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        NotificationFactory $factory,
        ObjectManager $manager,
        UserRepositoryInterface $appUserRepository,
        CustomerRepository $customerRepository,
        RouterInterface $router,
        TranslatorInterface $translator
    ) {
        $this->factory = $factory;
        $this->manager = $manager;
        $this->appUserRepository = $appUserRepository;
        $this->customerRepository = $customerRepository;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @param Article $article
     */
    public function notifyReviewers(Article $article): void
    {
        /** @var UserInterface[] $users */
        $users = $this->appUserRepository->findByRole('ROLE_REVIEWER');

        $this->notifyUsers($this->translator->trans('text.notification.article.ask_for_review', [
            '%ARTICLE_NAME%' => $article->getName(),
        ]), $article, $users);
    }

    /**
     * @param Article $article
     */
    public function notifyPublishers(Article $article): void
    {
        /** @var UserInterface[] $users */
        $users = $this->appUserRepository->findByRole('ROLE_PUBLISHER');

        $this->notifyUsers($this->translator->trans('text.notification.article.ask_for_publication', [
            '%ARTICLE_NAME%' => $article->getName(),
        ]), $article, $users);
    }

    public function notifySubscribers(Article $article): void
    {
        if (null === $product = $article->getProduct()) {
            return;
        }

        /** @var ProductSubscription[] $subscriptions */
        $customers = $this->customerRepository->findSubscribersToProductForOption($product, ProductSubscription::OPTION_FOLLOW_ARTICLES);

        $this->notifyCustomers($this->translator->trans('text.notification.article.new_publication', [
            '%PRODUCT_NAME%' => $product->getName(),
        ]), $article, $customers);
    }

    /**
     * @param string     $message
     * @param Article    $article
     * @param array|User $users
     */
    private function notifyUsers($message, Article $article, array $users): void
    {
        foreach ($users as $user) {
            $this->notifyCustomer($message, $article, $user->getCustomer());
        }

        $this->manager->flush();
    }

    /**
     * @param string                  $message
     * @param Article                 $article
     * @param array|CustomerInterface $customers
     */
    private function notifyCustomers($message, Article $article, array $customers): void
    {
        foreach ($customers as $customer) {
            $this->notifyCustomer($message, $article, $customer);
        }

        $this->manager->flush();
    }

    /**
     * @param string            $message
     * @param Article           $article
     * @param CustomerInterface $customer
     */
    private function notifyCustomer($message, Article $article, CustomerInterface $customer): void
    {
        $notification = $this->factory->createForArticle($article, $customer);
        $notification->setTarget($this->router->generate('app_frontend_article_show', [
            'slug' => $article->getSlug(),
        ]));
        $notification->setMessage($message);

        $this->manager->persist($notification);
    }
}
