<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service;

interface EmailCheckerInterface
{
    public function hasRecipient(string $recipient): bool;

    public function hasMessageTo(string $message, string $recipient): bool;

    public function countMessagesTo(string $recipient): int;
}
