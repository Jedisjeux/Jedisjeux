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

use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AuthorizationChecker implements AuthorizationCheckerInterface
{
    private $tokenStorage;
    private $accessDecisionManager;
    private $authenticationManager;
    private $alwaysAuthenticate;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, AccessDecisionManagerInterface $accessDecisionManager, bool $alwaysAuthenticate = false)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->accessDecisionManager = $accessDecisionManager;
        $this->alwaysAuthenticate = $alwaysAuthenticate;
    }

    /**
     * {@inheritdoc}
     */
    final public function isGranted($attributes, $subject = null)
    {
        if (null === ($token = $this->tokenStorage->getToken())) {
            return false;
        }

        if ($this->alwaysAuthenticate || !$token->isAuthenticated()) {
            $this->tokenStorage->setToken($token = $this->authenticationManager->authenticate($token));
        }

        if (!\is_array($attributes)) {
            $attributes = array($attributes);
        }

        return $this->accessDecisionManager->decide($token, $attributes, $subject);
    }
}
