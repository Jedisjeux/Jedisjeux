<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Setup;

use App\Behat\Service\SharedStorageInterface;
use App\Entity\Customer;
use App\Entity\User;
use App\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;

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
     * @var ExampleFactoryInterface
     */
    protected $appUserFactory;

    /**
     * @var EntityRepository
     */
    protected $customerRepository;

    /**
     * CustomerContext constructor.
     *
     * @param SharedStorageInterface $sharedStorage
     * @param ExampleFactoryInterface $appUserFactory
     * @param EntityRepository $customerRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ExampleFactoryInterface $appUserFactory,
        EntityRepository $customerRepository)
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
