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
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductBoxNotificationManager
{
    /**
     * @var RepositoryInterface|UserRepository
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
     * @param RepositoryInterface $userRepository
     * @param FactoryInterface    $notificationFactory
     * @param RouterInterface     $router
     * @param ObjectManager       $manager
     * @param TranslatorInterface $translator
     */
    public function __construct(
        RepositoryInterface $userRepository,
        FactoryInterface $notificationFactory,
        RouterInterface $router,
        TranslatorInterface $translator,
        ObjectManager $manager
    ) {
        $this->userRepository = $userRepository;
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

        $this->notifyUsers($this->translator->trans('text.notification.product_box.ask_for_review', [
            '%PRODUCT_NAME%' => $productBox->getProduct()->getName(),
        ]), $productBox, $users);
    }

    /**
     * @param string     $message
     * @param ProductBox $productBox
     * @param array      $users
     */
    private function notifyUsers($message, ProductBox $productBox, array $users)
    {
        foreach ($users as $user) {
            $notification = $this->notificationFactory->createForProductBox($productBox, $user->getCustomer());
            $notification->setTarget($this->router->generate('app_backend_product_box_update', [
                'id' => $productBox->getId(),
            ]));
            $notification->setMessage($message);

            $this->manager->persist($notification);
        }

        $this->manager->flush();
    }
}
