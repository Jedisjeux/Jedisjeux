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

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;

/**
 * Exchanges the code the provider sent back for the profile of the person.
 */
class OAuthProfileFetcher
{
    public function __construct(private readonly ClientRegistry $clientRegistry)
    {
    }

    public function fetch(string $provider): OAuthProfile
    {
        $client = $this->clientRegistry->getClient($provider);
        $accessToken = $client->getAccessToken();
        $owner = $client->fetchUserFromToken($accessToken);

        return match (true) {
            $owner instanceof GoogleUser => new OAuthProfile(
                $provider,
                (string) $owner->getId(),
                $owner->getEmail(),
                // Google vouches for gmail addresses and the ones of a hosted domain only
                $owner->isEmailTrustworthy(),
                $owner->getName(),
            ),
            $owner instanceof GithubResourceOwner => $this->createGithubProfile($provider, $owner, $client->getOAuth2Provider(), $accessToken),
            $owner instanceof FacebookUser => new OAuthProfile(
                $provider,
                (string) $owner->getId(),
                $owner->getEmail(),
                // Facebook does not tell whether the email was confirmed
                false,
                $owner->getName(),
            ),
            default => throw new \LogicException(\sprintf('The "%s" OAuth provider is not supported.', $provider)),
        };
    }

    private function createGithubProfile(string $provider, GithubResourceOwner $owner, AbstractProvider $oauthProvider, AccessToken $accessToken): OAuthProfile
    {
        $email = null;
        $emailVerified = false;

        // The profile email can be hidden or missing, the email list says which address is the primary verified one
        $request = $oauthProvider->getAuthenticatedRequest(AbstractProvider::METHOD_GET, 'https://api.github.com/user/emails', $accessToken);
        $emails = $oauthProvider->getParsedResponse($request);

        foreach (\is_array($emails) ? $emails : [] as $candidate) {
            if (\is_array($candidate) && true === ($candidate['primary'] ?? false) && isset($candidate['email'])) {
                $email = (string) $candidate['email'];
                $emailVerified = true === ($candidate['verified'] ?? false);

                break;
            }
        }

        return new OAuthProfile(
            $provider,
            (string) $owner->getId(),
            $email ?? $owner->getEmail(),
            $emailVerified,
            $owner->getNickname(),
        );
    }
}
