<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Service;

use Monofony\Bundle\CoreBundle\Tests\Behat\Service\SecurityServiceInterface;
use Monofony\Bundle\CoreBundle\Tests\Behat\Service\SharedSecurityServiceInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

final class SharedSecurityService implements SharedSecurityServiceInterface
{
    /** @var SecurityServiceInterface */
    private $adminSecurityService;

    /**
     * {@inheritdoc}
     */
    public function __construct(SecurityServiceInterface $adminSecurityService)
    {
        $this->adminSecurityService = $adminSecurityService;
    }

    /**
     * {@inheritdoc}
     */
    public function performActionAsAdminUser(UserInterface $adminUser, callable $action)
    {
        $this->performActionAs($this->adminSecurityService, $adminUser, $action);
    }

    private function performActionAs(SecurityServiceInterface $securityService, UserInterface $user, callable $action)
    {
        try {
            $token = $securityService->getCurrentToken();
        } catch (TokenNotFoundException $exception) {
            $token = null;
        }

        $securityService->logIn($user);
        $action();

        if (null === $token) {
            $securityService->logOut();

            return;
        }

        $securityService->restoreToken($token);
    }
}