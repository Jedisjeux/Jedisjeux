<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service;

use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

interface SecurityServiceInterface
{
    public function logIn(UserInterface $user);

    public function logOut();

    public function getCurrentToken(): TokenInterface;

    public function restoreToken(TokenInterface $token);
}
