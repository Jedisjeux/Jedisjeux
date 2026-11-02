<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service;

use App\Tests\Behat\Bridge\NotificationType;
use App\Tests\Behat\Exception\NotificationExpectationMismatchException;

interface NotificationCheckerInterface
{
    /** @throws NotificationExpectationMismatchException */
    public function checkNotification(string $message, NotificationType $type);
}
