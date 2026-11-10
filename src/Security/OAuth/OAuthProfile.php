<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Security\OAuth;

/**
 * What Jedisjeux keeps from a provider account: no access token, only what identifies the person.
 */
final readonly class OAuthProfile
{
    public function __construct(
        public string $provider,
        public string $identifier,
        public ?string $email,
        // Only a verified email proves the person owns it, so only it can be matched with an existing account
        public bool $emailVerified,
        public ?string $nickname,
    ) {
    }

    /**
     * @return array{provider: string, identifier: string, email: ?string, emailVerified: bool, nickname: ?string}
     */
    public function toArray(): array
    {
        return [
            'provider' => $this->provider,
            'identifier' => $this->identifier,
            'email' => $this->email,
            'emailVerified' => $this->emailVerified,
            'nickname' => $this->nickname,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['provider'],
            (string) $data['identifier'],
            isset($data['email']) ? (string) $data['email'] : null,
            (bool) ($data['emailVerified'] ?? false),
            isset($data['nickname']) ? (string) $data['nickname'] : null,
        );
    }
}
