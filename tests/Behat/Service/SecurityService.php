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

use App\Tests\Behat\Bridge\Service\AbstractSecurityService;
use App\Tests\Behat\Bridge\Service\Setter\CookieSetterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;

final class SecurityService extends AbstractSecurityService
{
    public function __construct(RequestStack $requestStack, CookieSetterInterface $cookieSetter, SessionFactoryInterface $sessionFactory)
    {
        parent::__construct($requestStack, $cookieSetter, 'user', $sessionFactory);
    }
}
