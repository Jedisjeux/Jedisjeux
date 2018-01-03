<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\NotificationManager;

use AppBundle\Factory\NotificationFactory;
use AppBundle\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductNotificationManager
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
     * @param ObjectManager $manager
     * @param UserRepository $userRepository
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     */
    public function __construct(NotificationFactory $factory, ObjectManager $manager, UserRepository $userRepository, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->factory = $factory;
        $this->manager = $manager;
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @param ProductInterface $product
     */
    public function notifyTranslators(ProductInterface $product)
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_TRANSLATOR');

        $this->notifyUsers($this->translator->trans('text.notification.product.ask_for_translation', [
            '%PRODUCT_NAME%' => $product->getName(),
        ]), $product, $users);
    }

    /**
     * @param ProductInterface $product
     */
    public function notifyReviewers(ProductInterface $product)
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_REVIEWER');

        $this->notifyUsers($this->translator->trans('text.notification.product.ask_for_review', [
            '%PRODUCT_NAME%' => $product->getName(),
        ]), $product, $users);
    }

    /**
     * @param ProductInterface $product
     */
    public function notifyPublishers(ProductInterface $product)
    {
        /** @var UserInterface[] $users */
        $users = $this->userRepository->findByRole('ROLE_PUBLISHER');

        $this->notifyUsers($this->translator->trans('text.notification.product.ask_for_publication', [
            '%PRODUCT_NAME%' => $product->getName(),
        ]), $product, $users);
    }

    /**
     * @param string $message
     * @param ProductInterface $product
     * @param array $users
     */
    protected function notifyUsers($message, ProductInterface $product, array $users)
    {
        foreach ($users as $user) {
            $notification = $this->factory->createForProduct($product, $user->getCustomer());
            $notification->setTarget($this->router->generate('sylius_product_show', [
                'slug' => $product->getSlug()
            ]));
            $notification->setMessage($message);

            $this->manager->persist($notification);
        }

        $this->manager->flush();
    }
}
