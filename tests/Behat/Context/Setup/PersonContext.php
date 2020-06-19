<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Setup;

use App\Tests\Behat\Service\SharedStorageInterface;
use App\Entity\Person;
use App\Fixture\Factory\PersonExampleFactory;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var PersonExampleFactory
     */
    protected $personFactory;

    /**
     * @var RepositoryInterface
     */
    protected $personRepository;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param PersonExampleFactory   $personFactory
     * @param RepositoryInterface    $personRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        PersonExampleFactory $personFactory,
        RepositoryInterface $personRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->personFactory = $personFactory;
        $this->personRepository = $personRepository;
    }

    /**
     * @Given there is person with first name :firstName and last name :lastName
     */
    public function thereIsPersonWithFirstNameAndLastName(string $firstName, string $lastName)
    {
        /** @var Person $person */
        $person = $this->personFactory->create([
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        $this->personRepository->add($person);
        $this->sharedStorage->set('person', $person);
    }

    /**
     * @Given there is person with name :name
     */
    public function thereIsPersonWithName(string $name)
    {
        list($firstName, $lastName) = explode(' ', $name);

        $this->thereIsPersonWithFirstNameAndLastName($firstName, $lastName);
    }
}
