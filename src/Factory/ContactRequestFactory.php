<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\ContactRequest;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ContactRequestFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var CustomerContextInterface
     */
    protected $customerContext;

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @param CustomerContextInterface $customerContext
     */
    public function setCustomerContext(CustomerContextInterface $customerContext)
    {
        $this->customerContext = $customerContext;
    }

    /**
     * @return ContactRequest
     */
    public function createNew()
    {
        /** @var ContactRequest $contactRequest */
        $contactRequest = new $this->className;

        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();

        if (null !== $customer) {
            $contactRequest->setEmail($customer->getEmail());
        }

        return $contactRequest;
    }

}
