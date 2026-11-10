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

namespace App\Controller;

use App\Emails;
use App\Entity\User;
use App\Form\Type\OAuthRegistrationType;
use App\Security\OAuth\OAuthAccountAlreadyLinkedException;
use App\Security\OAuth\OAuthAccountManager;
use App\Security\OAuth\OAuthAuthenticator;
use App\Security\OAuth\OAuthProfile;
use App\Security\OAuth\OAuthProfileFetcher;
use App\Security\OAuth\OAuthProviders;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Exception\OAuth2ClientException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class OAuthController extends AbstractController
{
    private const PROVIDERS = OAuthProviders::GOOGLE.'|'.OAuthProviders::FACEBOOK.'|'.OAuthProviders::GITHUB;

    public function __construct(
        private readonly OAuthProviders $providers,
        private readonly OAuthAccountManager $accountManager,
    ) {
    }

    #[Route('/connect/{provider}', name: 'app_oauth_connect', requirements: ['provider' => self::PROVIDERS], methods: ['GET'])]
    public function connect(string $provider, ClientRegistry $clientRegistry): RedirectResponse
    {
        if (!$this->providers->isEnabled($provider)) {
            throw $this->createNotFoundException();
        }

        return $clientRegistry->getClient($provider)->redirect($this->providers->getScopes($provider), []);
    }

    /**
     * The authenticator signs in the visitors, so only a signed-in person reaches this action, to link the provider account.
     */
    #[Route('/connect/{provider}/check', name: OAuthAuthenticator::CHECK_ROUTE, requirements: ['provider' => self::PROVIDERS], methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function check(string $provider, OAuthProfileFetcher $profileFetcher): RedirectResponse
    {
        $user = $this->getUser();
        \assert($user instanceof User);

        try {
            $this->accountManager->link($user, $profileFetcher->fetch($provider));
            $this->addFlash('success', 'app.oauth.linked');
        } catch (OAuth2ClientException|IdentityProviderException) {
            $this->addFlash('error', 'app.oauth.failure');
        } catch (OAuthAccountAlreadyLinkedException) {
            $this->addFlash('error', 'app.oauth.already_linked');
        }

        return $this->redirectToRoute('app_oauth_accounts');
    }

    #[Route('/connect/register', name: 'app_oauth_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        Security $security,
        SenderInterface $sender,
        EventDispatcherInterface $eventDispatcher,
        TranslatorInterface $translator,
    ): Response {
        $session = $request->getSession();
        $pendingRegistration = $session->get(OAuthAuthenticator::PENDING_REGISTRATION_SESSION_KEY);
        if (!\is_array($pendingRegistration) || null !== $this->getUser()) {
            return $this->redirectToRoute('sylius_frontend_login');
        }

        $profile = OAuthProfile::fromArray($pendingRegistration);

        $form = $this->createForm(OAuthRegistrationType::class, [
            'username' => $profile->nickname,
            'email' => $profile->email,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array{username: string, email: string} $data */
            $data = $form->getData();

            if ($this->accountManager->isEmailTaken($data['email'])) {
                // Never attached to the existing account: the person has to sign in with it, then link the provider
                $form->get('email')->addError(new FormError($translator->trans('app.oauth.email_taken', [], 'validators')));
            } else {
                $customer = $this->accountManager->createUser($profile, $data['username'], $data['email']);
                $user = $customer->getUser();
                \assert($user instanceof User);

                foreach ($validator->validate($user, null, ['sylius']) as $violation) {
                    $form->get('username')->addError(new FormError((string) $violation->getMessage()));
                }
            }

            if (isset($customer, $user) && $form->isValid()) {
                $entityManager->persist($customer);
                $entityManager->flush();
                $session->remove(OAuthAuthenticator::PENDING_REGISTRATION_SESSION_KEY);

                if (!$user->isEnabled()) {
                    // Welcome email and verification link, as the registration form sends
                    $eventDispatcher->dispatch(new GenericEvent($customer), 'sylius.customer.post_register');
                    $this->addFlash('success', 'sylius.customer.register');

                    return $this->redirectToRoute('app_homepage');
                }

                $sender->send(Emails::USER_REGISTRATION, [(string) $customer->getEmail()], ['customer' => $customer]);
                $this->addFlash('success', 'app.oauth.registered');

                return $security->login($user, OAuthAuthenticator::class, 'main')
                    ?? $this->redirectToRoute('app_homepage');
            }
        }

        return $this->render('frontend/oauth/register.html.twig', [
            'form' => $form,
            'provider' => $profile->provider,
            'emailVerified' => $profile->emailVerified,
        ]);
    }

    #[Route('/mon-compte/connexions', name: 'app_oauth_accounts', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function accounts(): Response
    {
        $user = $this->getUser();
        \assert($user instanceof User);

        return $this->render('frontend/account/oauth.html.twig', [
            'user' => $user,
            'providers' => $this->providers->getEnabled(),
            'canUnlink' => $this->accountManager->canUnlink($user),
        ]);
    }

    #[Route('/mon-compte/connexions/{provider}/unlink', name: 'app_oauth_unlink', requirements: ['provider' => self::PROVIDERS], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    #[IsCsrfTokenValid(new Expression('"oauth_unlink_" ~ args["provider"]'))]
    public function unlink(string $provider): RedirectResponse
    {
        $user = $this->getUser();
        \assert($user instanceof User);

        if (!$this->accountManager->canUnlink($user)) {
            $this->addFlash('error', 'app.oauth.last_access');

            return $this->redirectToRoute('app_oauth_accounts');
        }

        $this->accountManager->unlink($user, $provider);
        $this->addFlash('success', 'app.oauth.unlinked');

        return $this->redirectToRoute('app_oauth_accounts');
    }
}
