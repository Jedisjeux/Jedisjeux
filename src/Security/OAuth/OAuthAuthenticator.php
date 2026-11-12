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

use KnpU\OAuth2ClientBundle\Exception\OAuth2ClientException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Signs in with a provider account. A signed-in person instead links it to their account, which the controller handles.
 */
final class OAuthAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    public const CHECK_ROUTE = 'app_oauth_check';
    public const PENDING_REGISTRATION_SESSION_KEY = '_app_oauth_pending_registration';

    public function __construct(
        private readonly OAuthProfileFetcher $profileFetcher,
        private readonly OAuthAccountManager $accountManager,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly Security $security,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return self::CHECK_ROUTE === $request->attributes->get('_route') && null === $this->security->getUser();
    }

    public function authenticate(Request $request): Passport
    {
        $provider = (string) $request->attributes->get('provider');

        try {
            $profile = $this->profileFetcher->fetch($provider);
        } catch (OAuth2ClientException|IdentityProviderException $exception) {
            // The person refused the access, the state does not match or the code expired
            throw new CustomUserMessageAuthenticationException('app.oauth.failure', [], 0, $exception);
        }

        $user = $this->accountManager->findUser($profile);
        if (null === $user) {
            $request->getSession()->set(self::PENDING_REGISTRATION_SESSION_KEY, $profile->toArray());

            throw new OAuthRegistrationRequiredException();
        }

        return new SelfValidatingPassport(
            new UserBadge((string) $user->getUserIdentifier(), static fn () => $user),
            [(new RememberMeBadge())->enable()],
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        $this->removeTargetPath($request->getSession(), $firewallName);

        return new RedirectResponse($targetPath ?? $this->urlGenerator->generate('app_homepage'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($exception instanceof OAuthRegistrationRequiredException) {
            return new RedirectResponse($this->urlGenerator->generate('app_oauth_register'));
        }

        // Shown by the login page, as a failed password sign-in would be
        $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse($this->urlGenerator->generate('sylius_frontend_login'));
    }
}
