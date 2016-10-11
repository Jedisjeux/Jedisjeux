<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory;

use AppBundle\Entity\ContactRequest;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\User\Context\CustomerContextInterface;
use Sylius\Component\User\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ContactRequestFactory extends Factory
{
    /**
     * @var CustomerContextInterface
     */
    protected $customerContext;

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
        $contactRequest = parent::createNew();

        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();

        if (null !== $customer) {
            $contactRequest->setEmail($customer->getEmail());
        }

        return $contactRequest;
    }

}
