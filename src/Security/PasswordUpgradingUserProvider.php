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

namespace App\Security;

use Doctrine\Persistence\ObjectManager;
use Sylius\Component\User\Model\UserInterface as SyliusUserInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Saves the passwords rehashed on login, to migrate them from the legacy sha512 hasher.
 *
 * @implements UserProviderInterface<UserInterface>
 */
#[AsDecorator('sylius.app_user_provider.email_or_name_based')]
final class PasswordUpgradingUserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        #[AutowireDecorated]
        private UserProviderInterface $decorated,
        private ObjectManager $manager,
    ) {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->decorated->loadUserByIdentifier($identifier);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->decorated->refreshUser($user);
    }

    public function supportsClass(string $class): bool
    {
        return $this->decorated->supportsClass($class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof SyliusUserInterface) {
            return;
        }

        $user->setPassword($newHashedPassword);
        $this->manager->flush();
    }
}
