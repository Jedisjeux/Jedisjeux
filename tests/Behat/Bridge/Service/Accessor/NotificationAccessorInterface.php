<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service\Accessor;

use Behat\Mink\Element\NodeElement;

interface NotificationAccessorInterface
{
    /** @return NodeElement[] */
    public function getMessageElements(): array;
}
