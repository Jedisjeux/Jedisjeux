<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Mime\Email;
use Webmozart\Assert\Assert;

final class EmailChecker implements EmailCheckerInterface
{
    public function __construct(private CacheItemPoolInterface $cache)
    {
    }

    public function hasRecipient(string $recipient): bool
    {
        foreach ($this->getMailerMessages() as $email) {
            if ($this->isMessageTo($email, $recipient)) {
                return true;
            }
        }

        return false;
    }

    public function hasMessageTo(string $message, string $recipient): bool
    {
        $this->assertRecipientIsValid($recipient);
        foreach ($this->getMailerMessages() as $email) {
            if ($this->isMessageTo($email, $recipient)) {
                $text = trim(preg_replace('/\n+\s+/', ' ', strip_tags($email->getHtmlBody() ?? '')));
                if (str_contains($text, $message)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function countMessagesTo(string $recipient): int
    {
        $this->assertRecipientIsValid($recipient);
        $count = 0;
        foreach ($this->getMailerMessages() as $email) {
            if ($this->isMessageTo($email, $recipient)) {
                ++$count;
            }
        }

        return $count;
    }

    private function isMessageTo(Email $message, string $recipient): bool
    {
        foreach ($message->getTo() as $toRecipient) {
            if ($recipient === $toRecipient->getAddress()) {
                return true;
            }
        }

        return false;
    }

    private function assertRecipientIsValid(string $recipient): void
    {
        Assert::notEmpty($recipient, 'The recipient cannot be empty.');
        Assert::notEq(false, filter_var($recipient, FILTER_VALIDATE_EMAIL), 'Given recipient is not a valid email address.');
    }

    /** @return Email[] */
    private function getMailerMessages(): array
    {
        if (!$this->cache->hasItem(MessageSendCacher::CACHE_KEY)) {
            return [];
        }

        return $this->cache->getItem(MessageSendCacher::CACHE_KEY)->get();
    }
}
