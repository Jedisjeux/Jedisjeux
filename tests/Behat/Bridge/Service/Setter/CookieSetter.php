<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service\Setter;

use Behat\Mink\Driver\PantherDriver;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Session;
use DMore\ChromeDriver\ChromeDriver;
use FriendsOfBehat\SymfonyExtension\Driver\SymfonyDriver;
use Symfony\Component\BrowserKit\Cookie;

final class CookieSetter implements CookieSetterInterface
{
    public function __construct(private Session $minkSession, private \ArrayAccess $minkParameters)
    {
    }

    public function setCookie($name, $value): void
    {
        $this->prepareMinkSessionIfNeeded();
        $driver = $this->minkSession->getDriver();
        if (($driver instanceof PantherDriver || $driver instanceof ChromeDriver) && !$driver->isStarted()) {
            $driver->start();
        }

        if ($driver instanceof SymfonyDriver) {
            $driver->getClient()->getCookieJar()->set(new Cookie(
                $name,
                $value,
                null,
                null,
                parse_url($this->minkParameters['base_url'], PHP_URL_HOST)
            ));

            return;
        }

        $this->minkSession->setCookie($name, $value);
    }

    private function prepareMinkSessionIfNeeded(): void
    {
        $driver = $this->minkSession->getDriver();
        if ($driver instanceof SymfonyDriver) {
            return;
        }

        if ($driver instanceof Selenium2Driver || $driver instanceof ChromeDriver || $driver instanceof PantherDriver) {
            $this->minkSession->visit(rtrim($this->minkParameters['base_url'], '/').'/');

            return;
        }

        if (!str_contains($this->minkSession->getCurrentUrl(), $this->minkParameters['base_url'])) {
            $this->minkSession->visit(rtrim($this->minkParameters['base_url'], '/').'/');
        }
    }
}
