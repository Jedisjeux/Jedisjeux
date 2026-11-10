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

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Attribute\AsTwigFunction;

/**
 * The providers offered to sign in: the ones with a client id, so an environment without credentials hides them.
 */
class OAuthProviders
{
    public const GOOGLE = 'google';
    public const FACEBOOK = 'facebook';
    public const GITHUB = 'github';

    /** @var array<string, list<string>> */
    private const SCOPES = [
        self::GOOGLE => ['openid', 'email', 'profile'],
        self::FACEBOOK => ['email', 'public_profile'],
        self::GITHUB => ['read:user', 'user:email'],
    ];

    /** @var array<string, string> */
    private readonly array $clientIds;

    public function __construct(
        #[Autowire(env: 'OAUTH_GOOGLE_CLIENT_ID')]
        string $googleClientId,
        #[Autowire(env: 'OAUTH_FACEBOOK_CLIENT_ID')]
        string $facebookClientId,
        #[Autowire(env: 'OAUTH_GITHUB_CLIENT_ID')]
        string $githubClientId,
    ) {
        $this->clientIds = [
            self::GOOGLE => $googleClientId,
            self::FACEBOOK => $facebookClientId,
            self::GITHUB => $githubClientId,
        ];
    }

    /**
     * @return list<string>
     */
    #[AsTwigFunction('oauth_providers')]
    public function getEnabled(): array
    {
        return array_keys(array_filter($this->clientIds, static fn (string $clientId): bool => '' !== $clientId));
    }

    public function isEnabled(string $provider): bool
    {
        return \in_array($provider, $this->getEnabled(), true);
    }

    /**
     * @return list<string>
     */
    public function getScopes(string $provider): array
    {
        return self::SCOPES[$provider] ?? [];
    }
}
