<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service;

use App\Tests\Behat\Bridge\NotificationType;
use App\Tests\Behat\Bridge\Service\Accessor\NotificationAccessorInterface;
use App\Tests\Behat\Exception\NotificationExpectationMismatchException;
use Webmozart\Assert\Assert;

final class NotificationChecker implements NotificationCheckerInterface
{
    public function __construct(private NotificationAccessorInterface $notificationAccessor)
    {
    }

    public function checkNotification(string $message, NotificationType $type): void
    {
        foreach ($this->notificationAccessor->getMessageElements() as $element) {
            if (str_contains($element->getText(), $message) && $element->hasClass($this->resolveClass($type))) {
                return;
            }
        }

        throw new NotificationExpectationMismatchException($type, $message);
    }

    private function resolveClass(NotificationType $type): string
    {
        $classes = [
            'failure' => 'negative',
            'info' => 'info',
            'success' => 'positive',
        ];
        Assert::keyExists($classes, (string) $type);

        return $classes[(string) $type];
    }
}
