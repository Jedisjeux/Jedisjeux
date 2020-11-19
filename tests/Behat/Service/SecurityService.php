<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Service;

use Monofony\Bridge\Behat\Service\AbstractSecurityService;
use Monofony\Bridge\Behat\Service\Setter\CookieSetterInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SecurityService extends AbstractSecurityService
{
    public function __construct(SessionInterface $session, CookieSetterInterface $cookieSetter)
    {
        parent::__construct($session, $cookieSetter, 'user');
    }
}
