<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service\Accessor;

use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Session;

final class NotificationAccessor implements NotificationAccessorInterface
{
    public function __construct(private Session $session)
    {
    }

    public function getMessageElements(): array
    {
        $elements = $this->session->getPage()->findAll('css', '.sylius-flash-message');
        if ([] === $elements) {
            throw new ElementNotFoundException($this->session->getDriver(), 'message element', 'css', '.sylius-flash-message');
        }

        return $elements;
    }
}
