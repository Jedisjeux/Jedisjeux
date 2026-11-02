<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Transform;

use Sylius\Component\User\Model\UserInterface as AdminUserInterface;
use Behat\Behat\Context\Context;
use App\Tests\Behat\Bridge\Service\SharedStorageInterface;

final class AdminUserContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    public function __construct(SharedStorageInterface $sharedStorage)
    {
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Transform /^(I|my)$/
     */
    public function getLoggedAdminUser(): ?AdminUserInterface
    {
        return $this->sharedStorage->get('administrator');
    }
}
