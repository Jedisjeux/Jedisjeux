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

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * No Jedisjeux account matches the provider account yet, the person has to pick a username first.
 */
final class OAuthRegistrationRequiredException extends AuthenticationException
{
    public function getMessageKey(): string
    {
        return 'No account is linked to this provider account yet.';
    }
}
