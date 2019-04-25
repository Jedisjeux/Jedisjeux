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

use App\Entity\ProductFile;
use App\Event\ProductFileEvents;
use App\NotificationManager\ProductFileNotificationManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

class NotifyReviewersForNewFileSubscriber implements EventSubscriberInterface
{
    /**
     * @var ProductFileNotificationManager
     */
    private $productFileNotificationManager;

    /**
     * @param ProductFileNotificationManager $productFileNotificationManager
     */
    public function __construct(ProductFileNotificationManager $productFileNotificationManager)
    {
        $this->productFileNotificationManager = $productFileNotificationManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ProductFileEvents::POST_CREATE => 'notifyReviewers',
        ];
    }

    public function notifyReviewers(GenericEvent $event): void
    {
        /** @var ProductFile $file */
        $file = $event->getSubject();

        Assert::isInstanceOf($file, ProductFile::class);

        $this->productFileNotificationManager->notifyReviewers($file);
    }
}
