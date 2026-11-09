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

use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductNotificationManager
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

    public function notifyTranslators(ProductInterface $product): void
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_TRANSLATOR');

        $this->notifyUsers($this->translator->trans('text.notification.product.ask_for_translation', [
            '%PRODUCT_NAME%' => $product->getName(),
        ]), $product, $users);
    }

    public function notifyReviewers(ProductInterface $product): void
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_REVIEWER');

        $this->notifyUsers($this->translator->trans('text.notification.product.ask_for_review', [
            '%PRODUCT_NAME%' => $product->getName(),
        ]), $product, $users);
    }

    public function notifyPublishers(ProductInterface $product): void
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_PUBLISHER');

        $this->notifyUsers($this->translator->trans('text.notification.product.ask_for_publication', [
            '%PRODUCT_NAME%' => $product->getName(),
        ]), $product, $users);
    }

    /**
     * @param string $message
     */
    private function notifyUsers($message, ProductInterface $product, array $users)
    {
        foreach ($users as $user) {
            $notification = $this->factory->createForProduct($product, $user->getCustomer());
            $notification->setTarget($this->router->generate('sylius_frontend_product_show', [
                'slug' => $product->getSlug(),
            ]));
            $notification->setMessage($message);

            $this->manager->persist($notification);
        }

        $this->manager->flush();
    }
}
