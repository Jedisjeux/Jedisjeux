<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\View;

namespace App\Security;

use Sylius\Component\User\Model\UserInterface as SyliusUserInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserChecker as BaseUserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Sylius users do not implement AdvancedUserInterface anymore, so the Symfony user checker ignores their status.
 */
final class UserChecker extends BaseUserChecker
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof SyliusUserInterface) {
            parent::checkPreAuth($user);

            return;
        }

        if (!$user->isAccountNonLocked()) {
            $exception = new LockedException('User account is locked.');
            $exception->setUser($user);

            throw $exception;
        }

        if (!$user->isEnabled()) {
            $exception = new DisabledException('User account is disabled.');
            $exception->setUser($user);

            throw $exception;
        }

        if (!$user->isAccountNonExpired()) {
            $exception = new AccountExpiredException('User account has expired.');
            $exception->setUser($user);

            throw $exception;
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof SyliusUserInterface) {
            parent::checkPostAuth($user);

            return;
        }

        if (!$user->isCredentialsNonExpired()) {
            $exception = new CredentialsExpiredException('User credentials have expired.');
            $exception->setUser($user);

            throw $exception;
        }
    }
}
