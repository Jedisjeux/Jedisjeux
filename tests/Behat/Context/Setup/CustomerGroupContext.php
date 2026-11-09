<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class CustomerGroupContext implements Context
{
    public function __construct(
        #[Autowire(service: 'sylius.factory.customer_group')]
        private readonly FactoryInterface $customerGroupFactory,
        #[Autowire(service: 'sylius.repository.customer_group')]
        private readonly RepositoryInterface $customerGroupRepository,
    ) {
    }

    /**
     * @Given there is (also) a customer group :name
     */
    public function thereIsACustomerGroup(string $name): void
    {
        /** @var CustomerGroupInterface $customerGroup */
        $customerGroup = $this->customerGroupFactory->createNew();
        $customerGroup->setName($name);
        $customerGroup->setCode(strtolower(str_replace(' ', '_', $name)));

        $this->customerGroupRepository->add($customerGroup);
    }
}
