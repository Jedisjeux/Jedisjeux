<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Crud;

use Behat\Mink\Exception\ElementNotFoundException;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;

interface CreatePageInterface extends SymfonyPageInterface
{
    /** @throws ElementNotFoundException */
    public function getValidationMessage(string $element): string;

    /** @throws ElementNotFoundException */
    public function create(): void;
}
