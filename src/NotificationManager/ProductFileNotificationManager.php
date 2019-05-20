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

use App\Entity\ProductFile;
use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductFileNotificationManager
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
     * @param ProductFile $productFile
     */
    public function notifyModerators(ProductFile $productFile)
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_MODERATOR');

        $target = $this->router->generate('app_backend_product_file_update', [
            'id' => $productFile->getId(),
        ]);

        $this->notifyUsers(
            $this->translator->trans('text.notification.product_file.ask_for_review', [
                '%title%' => $productFile->getTitle(),
            ]),
            $productFile,
            $target,
            $users
        );
    }

    /**
     * @param ProductFile $productFile
     */
    public function notifyAuthor(ProductFile $productFile)
    {
        $target = $this->router->generate('app_frontend_account_games_library');

        if (null === $author = $productFile->getAuthor()) {
            return;
        }

        if (null === $user = $author->getUser()) {
            return;
        }

        $this->notifyUsers(
            $this->translator->trans(sprintf('text.notification.product_file.%s', $productFile->getStatus()), [
                '%title%' => $productFile->getTitle(),
            ]),
            $productFile,
            $target,
            [$user]
        );
    }

    /**
     * @param string      $message
     * @param ProductFile $productFile
     * @param array       $users
     */
    private function notifyUsers($message, ProductFile $productFile, string $target, array $users)
    {
        foreach ($users as $user) {
            $notification = $this->notificationFactory->createForProductFile($productFile, $user->getCustomer());
            $notification->setTarget($target);
            $notification->setMessage($message);

            $this->manager->persist($notification);
        }

        $this->manager->flush();
    }
}
