<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Transform;

use AppBundle\Entity\Person;
use AppBundle\Entity\Topic;
use Behat\Behat\Context\Context;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
final class CustomerContext implements Context
{
    /**
     * @var EntityRepository
     */
    private $customerRepository;

    /**
     * PersonContext constructor.
     *
     * @param EntityRepository $topicRepository
     */
    public function __construct(EntityRepository $topicRepository)
    {
        $this->customerRepository = $topicRepository;
    }

    /**
     * @Transform /^customer "([^"]+)"$/
     * @Transform :customer
     *
     * @param string $email
     *
     * @return CustomerInterface
     */
    public function getCustomerByEmail($email)
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerRepository->findOneBy(['email' => $email]);

        Assert::notNull(
            $customer,
            sprintf('Customer with email "%s" does not exist', $email)
        );

        return $customer;
    }
}
