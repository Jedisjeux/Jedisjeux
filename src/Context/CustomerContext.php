<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Context;

use App\Entity\User;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CustomerContext implements CustomerContextInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Gets customer based on currently logged user.
     */
    public function getCustomer(): ?CustomerInterface
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') && $token->getUser() instanceof User) {
            return $token->getUser()->getCustomer();
        }

        return null;
    }
}
