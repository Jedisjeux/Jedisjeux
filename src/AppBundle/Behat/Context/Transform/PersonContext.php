<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Transform;

use AppBundle\Entity\Person;
use Behat\Behat\Context\Context;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 */
final class PersonContext implements Context
{
    /**
     * @var EntityRepository
     */
    private $personRepository;

    /**
     * PersonContext constructor.
     *
     * @param EntityRepository $personRepository
     */
    public function __construct(EntityRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @Transform /^person "([^"]+)"$/
     * @Transform :person
     *
     * @return Person
     */
    public function getPersonByFullName($fullName)
    {
        list($firstName, $lastName) = explode(' ', $fullName);

        /** @var Person $person */
        $person = $this->personRepository->findOneBy(['firstName' => $firstName, 'lastName' => $lastName]);

        Assert::notNull(
            $person,
            sprintf('Person with name "%s" does not exist', $fullName)
        );

        return $person;
    }
}
