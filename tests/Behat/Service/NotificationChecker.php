<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Service;

use App\Tests\Behat\Exception\NotificationExpectationMismatchException;
use App\Tests\Behat\NotificationType;
use App\Tests\Behat\Service\Accessor\NotificationAccessorInterface;
use Monofony\Bundle\CoreBundle\Tests\Behat\Service\NotificationCheckerInterface;

final class NotificationChecker implements NotificationCheckerInterface
{
    /**
     * @var NotificationAccessorInterface
     */
    private $notificationAccessor;

    public function __construct(NotificationAccessorInterface $notificationAccessor)
    {
        $this->notificationAccessor = $notificationAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function checkNotification($message, NotificationType $type)
    {
        if ($this->hasType($type) && $this->hasMessage($message)) {
            return;
        }

        throw new NotificationExpectationMismatchException(
            $type,
            $message,
            $this->notificationAccessor->getType(),
            $this->notificationAccessor->getMessage()
        );
    }

    private function hasType(NotificationType $type): bool
    {
        return $type === $this->notificationAccessor->getType();
    }

    private function hasMessage(string $message): bool
    {
        return false !== strpos($this->notificationAccessor->getMessage(), $message);
    }
}
