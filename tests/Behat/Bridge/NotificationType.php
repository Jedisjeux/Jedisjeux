<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge;

final class NotificationType
{
    private static array $types = [];

    private function __construct(private string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function failure(): self
    {
        return self::getTyped('failure');
    }

    public static function success(): self
    {
        return self::getTyped('success');
    }

    private static function getTyped(string $type): self
    {
        return self::$types[$type] ??= new self($type);
    }
}
