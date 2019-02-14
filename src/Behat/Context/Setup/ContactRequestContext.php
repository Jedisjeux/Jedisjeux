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
use App\Entity\ContactRequest;
use App\Fixture\Factory\ContactRequestExampleFactory;
use App\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ContactRequestContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ContactRequestExampleFactory
     */
    private $contactRequestFactory;

    /**
     * @var RepositoryInterface
     */
    private $contactRequestRepository;

    /**
     * @param SharedStorageInterface       $sharedStorage
     * @param ContactRequestExampleFactory $contactRequestFactory
     * @param RepositoryInterface          $contactRequestRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ContactRequestExampleFactory $contactRequestFactory,
        RepositoryInterface $contactRequestRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->contactRequestFactory = $contactRequestFactory;
        $this->contactRequestRepository = $contactRequestRepository;
    }

    /**
     * @Given there is (also )a contact request from :email
     */
    public function thereIsContactRequest($email)
    {
        /** @var ContactRequest $contactRequest */
        $contactRequest = $this->contactRequestFactory->create([
            'email' => $email,
        ]);

        $this->contactRequestRepository->add($contactRequest);
        $this->sharedStorage->set('contact_request', $contactRequest);
    }
}
