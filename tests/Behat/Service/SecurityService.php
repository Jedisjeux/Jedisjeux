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

use Monofony\Bundle\CoreBundle\Tests\Behat\Service\AbstractSecurityService;
use Monofony\Bundle\CoreBundle\Tests\Behat\Service\Setter\CookieSetterInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SecurityService extends AbstractSecurityService
{
    public function __construct(SessionInterface $session, CookieSetterInterface $cookieSetter)
    {
        parent::__construct($session, $cookieSetter, 'user');
    }
}
