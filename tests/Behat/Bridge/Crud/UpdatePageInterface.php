<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Crud;

use Behat\Mink\Exception\ElementNotFoundException;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;

interface UpdatePageInterface extends SymfonyPageInterface
{
    /** @throws ElementNotFoundException */
    public function getValidationMessage(string $element): string;

    public function hasResourceValues(array $parameters): bool;

    public function saveChanges(): void;
}
