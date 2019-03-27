<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\NotificationManager;

use App\Entity\ProductBox;
use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductBoxNotificationManager
{
    /**
     * @var UserRepositoryInterface|UserRepository
     */
    private $userRepository;

    /**
     * @var FactoryInterface|NotificationFactory
     */
    private $notificationFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @param UserRepositoryInterface $appUserRepository
     * @param FactoryInterface        $notificationFactory
     * @param RouterInterface         $router
     * @param ObjectManager           $manager
     * @param TranslatorInterface     $translator
     */
    public function __construct(
        UserRepositoryInterface $appUserRepository,
        FactoryInterface $notificationFactory,
        RouterInterface $router,
        TranslatorInterface $translator,
        ObjectManager $manager
    ) {
        $this->userRepository = $appUserRepository;
        $this->notificationFactory = $notificationFactory;
        $this->router = $router;
        $this->translator = $translator;
        $this->manager = $manager;
    }

    /**
     * @param ProductBox $productBox
     */
    public function notifyReviewers(ProductBox $productBox)
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_REVIEWER');

        $target = $this->router->generate('app_backend_product_box_update', [
            'id' => $productBox->getId(),
        ]);

        $this->notifyUsers(
            $this->translator->trans('text.notification.product_box.ask_for_review', [
                '%PRODUCT_NAME%' => $productBox->getProduct()->getName(),
            ]),
            $productBox,
            $target,
            $users
        );
    }

    /**
     * @param ProductBox $productBox
     */
    public function notifyAuthor(ProductBox $productBox)
    {
        $target = $this->router->generate('app_frontend_account_games_library');

        if (null === $author = $productBox->getAuthor()) {
            return;
        }

        if (null === $user = $author->getUser()) {
            return;
        }

        $this->notifyUsers(
            $this->translator->trans(sprintf('text.notification.product_box.%s', $productBox->getStatus()), [
                '%PRODUCT_NAME%' => $productBox->getProduct()->getName(),
            ]),
            $productBox,
            $target,
            [$user]
        );
    }

    /**
     * @param string     $message
     * @param ProductBox $productBox
     * @param array      $users
     */
    private function notifyUsers($message, ProductBox $productBox, string $target, array $users)
    {
        foreach ($users as $user) {
            $notification = $this->notificationFactory->createForProductBox($productBox, $user->getCustomer());
            $notification->setTarget($target);
            $notification->setMessage($message);

            $this->manager->persist($notification);
        }

        $this->manager->flush();
    }
}
