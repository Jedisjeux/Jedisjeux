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
use Doctrine\Common\Persistence\ObjectManager;
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
    protected $factory;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * ProductNotificationManager constructor.
     *
     * @param NotificationFactory $factory
     * @param ObjectManager       $manager
     * @param UserRepository      $userRepository
     * @param RouterInterface     $router
     * @param TranslatorInterface $translator
     */
    public function __construct(
        NotificationFactory $factory,
        ObjectManager $manager,
        UserRepository $userRepository,
        RouterInterface $router,
        TranslatorInterface $translator
    ) {
        $this->factory = $factory;
        $this->manager = $manager;
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @param Article $article
     */
    public function notifyReviewers(Article $article)
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_REVIEWER');

        $this->notifyUsers($this->translator->trans('text.notification.article.ask_for_review', [
            '%ARTICLE_NAME%' => $article->getName(),
        ]), $article, $users);
    }

    /**
     * @param Article $article
     */
    public function notifyPublishers(Article $article)
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_PUBLISHER');

        $this->notifyUsers($this->translator->trans('text.notification.article.ask_for_publication', [
            '%ARTICLE_NAME%' => $article->getName(),
        ]), $article, $users);
    }

    /**
     * @param string  $message
     * @param Article $article
     * @param array   $users
     */
    protected function notifyUsers($message, Article $article, array $users)
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
