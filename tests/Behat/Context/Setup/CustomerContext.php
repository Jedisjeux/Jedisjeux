<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Setup;

use App\Entity\User;
use App\Fixture\Factory\AppUserExampleFactory;
use Behat\Behat\Context\Context;
use Monofony\Bridge\Behat\Service\SharedStorageInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CustomerContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var AppUserExampleFactory
     */
    protected $appUserFactory;

    /**
     * @var RepositoryInterface
     */
    protected $customerRepository;

    /**
     * CustomerContext constructor.
     *
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        AppUserExampleFactory $appUserFactory,
        RepositoryInterface $customerRepository)
    {
        $this->sharedStorage = $sharedStorage;
        $this->appUserFactory = $appUserFactory;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Given there is (also )a customer with email :email
     *
     * @param string $email
     */
    public function thereIsCustomerWithEmail($email)
    {
        /** @var User $user */
        $user = $this->appUserFactory->create([
            'email' => $email,
        ]);

        $this->customerRepository->add($user);
        $this->sharedStorage->set('user', $user);
        $this->sharedStorage->set('customer', $user->getCustomer());
    }
}
