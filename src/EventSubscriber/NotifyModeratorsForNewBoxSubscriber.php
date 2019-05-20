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

namespace App\EventSubscriber;

use App\Entity\ProductBox;
use App\Event\ProductBoxEvents;
use App\NotificationManager\ProductBoxNotificationManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

class NotifyModeratorsForNewBoxSubscriber implements EventSubscriberInterface
{
    /**
     * @var ProductBoxNotificationManager
     */
    private $productBoxNotificationManager;

    /**
     * @param ProductBoxNotificationManager $productBoxNotificationManager
     */
    public function __construct(ProductBoxNotificationManager $productBoxNotificationManager)
    {
        $this->productBoxNotificationManager = $productBoxNotificationManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ProductBoxEvents::POST_CREATE => 'notifyModerators',
        ];
    }

    public function notifyModerators(GenericEvent $event): void
    {
        /** @var ProductBox $box */
        $box = $event->getSubject();

        Assert::isInstanceOf($box, ProductBox::class);

        $this->productBoxNotificationManager->notifyModerators($box);
    }
}
