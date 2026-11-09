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
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductFileNotificationManager
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $appUserRepository,
        private FactoryInterface $notificationFactory,
        private RouterInterface $router,
        private TranslatorInterface $translator,
        private ObjectManager $manager
    ) {
        $this->userRepository = $appUserRepository;
    }

    public function notifyModerators(ProductFile $productFile): void
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

    public function notifyAuthor(ProductFile $productFile): void
    {
        if (null === $author = $productFile->getAuthor()) {
            return;
        }

        if (null === $user = $author->getUser()) {
            return;
        }

        $target = $this->router->generate('app_frontend_account_games_library');

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
     * @param string $message
     */
    private function notifyUsers(string $message, ProductFile $productFile, string $target, array $users): void
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
