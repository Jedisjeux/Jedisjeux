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

use App\Entity\Customer;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\User\Model\UserOAuthInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Finds, links and creates the Jedisjeux accounts behind the provider accounts.
 */
class OAuthAccountManager
{
    /**
     * @param RepositoryInterface<UserOAuthInterface> $oauthRepository
     * @param FactoryInterface<UserOAuthInterface>    $oauthFactory
     * @param RepositoryInterface<Customer>           $customerRepository
     * @param FactoryInterface<Customer>              $customerFactory
     * @param FactoryInterface<User>                  $userFactory
     */
    public function __construct(
        #[Autowire(service: 'sylius.repository.admin_oauth_user')]
        private readonly RepositoryInterface $oauthRepository,
        #[Autowire(service: 'sylius.factory.admin_oauth_user')]
        private readonly FactoryInterface $oauthFactory,
        #[Autowire(service: 'sylius.repository.app_user')]
        private readonly UserRepository $userRepository,
        #[Autowire(service: 'sylius.repository.customer')]
        private readonly RepositoryInterface $customerRepository,
        #[Autowire(service: 'sylius.factory.customer')]
        private readonly FactoryInterface $customerFactory,
        #[Autowire(service: 'sylius.factory.app_user')]
        private readonly FactoryInterface $userFactory,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * The account already linked to the provider account, or the one owning its verified email, which gets linked on the way.
     */
    public function findUser(OAuthProfile $profile): ?User
    {
        $linkedUser = $this->findLinkedUser($profile);
        if (null !== $linkedUser) {
            return $linkedUser;
        }

        if (!$profile->emailVerified || null === $profile->email) {
            return null;
        }

        $user = $this->userRepository->findOneByEmail($profile->email);
        if (!$user instanceof User || null !== $user->getOAuthAccount($profile->provider)) {
            // Another account of this provider is already linked, the person has to sign in with it
            return null;
        }

        // The provider proved the email belongs to the person, as the verification link would have
        if (!$user->isVerified()) {
            $user->setVerifiedAt(new \DateTime());
            $user->setEmailVerificationToken(null);
            $user->setEnabled(true);
        }

        $this->addOAuthAccount($user, $profile);
        $this->entityManager->flush();

        return $user;
    }

    public function findLinkedUser(OAuthProfile $profile): ?User
    {
        $oauthAccount = $this->oauthRepository->findOneBy([
            'provider' => $profile->provider,
            'identifier' => $profile->identifier,
        ]);

        $user = $oauthAccount?->getUser();

        return $user instanceof User ? $user : null;
    }

    /**
     * @throws OAuthAccountAlreadyLinkedException
     */
    public function link(User $user, OAuthProfile $profile): void
    {
        $linkedUser = $this->findLinkedUser($profile);
        if ($linkedUser === $user) {
            return;
        }

        if (null !== $linkedUser || null !== $user->getOAuthAccount($profile->provider)) {
            throw new OAuthAccountAlreadyLinkedException();
        }

        $this->addOAuthAccount($user, $profile);
        $this->entityManager->flush();
    }

    public function unlink(User $user, string $provider): void
    {
        $oauthAccount = $user->getOAuthAccount($provider);
        if (null === $oauthAccount) {
            return;
        }

        $user->getOAuthAccounts()->removeElement($oauthAccount);
        $this->entityManager->remove($oauthAccount);
        $this->entityManager->flush();
    }

    /**
     * Without a password, the provider accounts are the only way in, so the last one stays.
     */
    public function canUnlink(User $user): bool
    {
        return null !== $user->getPassword() || $user->getOAuthAccounts()->count() > 1;
    }

    public function isEmailTaken(string $email): bool
    {
        return null !== $this->findCustomer($email)?->getUser();
    }

    /**
     * A new account without password, enabled right away when the provider verified its email.
     */
    public function createUser(OAuthProfile $profile, string $username, string $email): Customer
    {
        // A customer without account, as the registration form does
        $customer = $this->findCustomer($email) ?? $this->customerFactory->createNew();
        $customer->setEmail($email);

        $user = $this->userFactory->createNew();
        $user->setUsername($username);
        $customer->setUser($user);

        // The person may type another email than the provider one, which then needs the usual verification
        if ($profile->emailVerified && null !== $profile->email && 0 === strcasecmp($profile->email, $email)) {
            $user->setVerifiedAt(new \DateTime());
            $user->setEnabled(true);
        }

        $this->addOAuthAccount($user, $profile);

        return $customer;
    }

    private function findCustomer(string $email): ?Customer
    {
        $customer = $this->customerRepository->findOneBy(['emailCanonical' => mb_strtolower($email)]);

        return $customer instanceof Customer ? $customer : null;
    }

    private function addOAuthAccount(User $user, OAuthProfile $profile): void
    {
        $oauthAccount = $this->oauthFactory->createNew();
        $oauthAccount->setProvider($profile->provider);
        $oauthAccount->setIdentifier($profile->identifier);
        // Persisted along with the user, which cascades to its provider accounts
        $user->addOAuthAccount($oauthAccount);
    }
}
