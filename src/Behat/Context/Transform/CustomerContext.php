<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
final class CustomerContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var FactoryInterface
     */
    private $customerFactory;

    /**
     * PersonContext constructor.
     *
     * @param RepositoryInterface $topicRepository
     * @param FactoryInterface $customerFactory
     */
    public function __construct(RepositoryInterface $topicRepository, FactoryInterface $customerFactory)
    {
        $this->customerRepository = $topicRepository;
        $this->customerFactory = $customerFactory;
    }

    /**
     * @Transform /^customer "([^"]+)"$/
     * @Transform :customer
     *
     * @param string $email
     *
     * @return CustomerInterface
     */
    public function getOrCreateCustomerByEmail($email)
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerRepository->findOneBy(['email' => $email]);

        if (null === $customer) {
            /** @var CustomerInterface $customer */
            $customer = $this->customerFactory->createNew();
            $customer->setEmail($email);

            $this->customerRepository->add($customer);
        }

        return $customer;
    }
}
