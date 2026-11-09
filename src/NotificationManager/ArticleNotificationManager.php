<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\NotificationManager;

use App\Entity\Article;
use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleNotificationManager
{
    /**
     * ProductNotificationManager constructor.
     */
    public function __construct(
        private NotificationFactory $factory,
        private ObjectManager $manager,
        private UserRepository $userRepository,
        private RouterInterface $router,
        private TranslatorInterface $translator
    ) {
    }

    public function notifyReviewers(Article $article): void
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_REVIEWER');

        $this->notifyUsers($this->translator->trans('text.notification.article.ask_for_review', [
            '%ARTICLE_NAME%' => $article->getName(),
        ]), $article, $users);
    }

    public function notifyPublishers(Article $article): void
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_PUBLISHER');

        $this->notifyUsers($this->translator->trans('text.notification.article.ask_for_publication', [
            '%ARTICLE_NAME%' => $article->getName(),
        ]), $article, $users);
    }

    /**
     * @param string $message
     */
    private function notifyUsers($message, Article $article, array $users)
    {
        foreach ($users as $user) {
            $notification = $this->factory->createForArticle($article, $user->getCustomer());
            $notification->setTarget($this->router->generate('app_frontend_article_show', [
                'slug' => $article->getSlug(),
            ]));
            $notification->setMessage($message);

            $this->manager->persist($notification);
        }

        $this->manager->flush();
    }
}
