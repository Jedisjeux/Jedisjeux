<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Setup;

use AppBundle\Entity\Person;
use AppBundle\Entity\Topic;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CustomerContext implements Context
{
    /**
     * @var ExampleFactoryInterface
     */
    protected $customerFactory;

    /**
     * @var EntityRepository
     */
    protected $customerRepository;

    /**
     * CustomerContext constructor.
     *
     * @param ExampleFactoryInterface $customerFactory
     * @param EntityRepository $customerRepository
     */
    public function __construct(ExampleFactoryInterface $customerFactory, EntityRepository $customerRepository)
    {
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Given there is customer with email :email
     *
     * @param string $email
     */
    public function thereIsCustomerWithEmail($email)
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerFactory->create([
            'email' => $email,
        ]);

        $this->customerRepository->add($customer);
    }
}
