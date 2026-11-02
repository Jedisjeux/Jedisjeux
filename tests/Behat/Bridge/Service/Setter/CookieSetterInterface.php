<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service\Setter;

interface CookieSetterInterface
{
    public function setCookie($name, $value);
}
