<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Authorization;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Denies access instead of throwing when there is no token (e.g. on error pages).
 */
class AuthorizationChecker implements AuthorizationCheckerInterface
{
    private $tokenStorage;
    private $accessDecisionManager;

    public function __construct(TokenStorageInterface $tokenStorage, AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->accessDecisionManager = $accessDecisionManager;
    }

    /**
     * {@inheritdoc}
     */
    final public function isGranted($attribute, $subject = null): bool
    {
        if (null === ($token = $this->tokenStorage->getToken())) {
            return false;
        }

        return $this->accessDecisionManager->decide($token, [$attribute], $subject);
    }
}
