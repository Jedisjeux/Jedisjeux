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

use AppBundle\Behat\Service\SecurityServiceInterface;
use AppBundle\Behat\Service\SharedStorageInterface;
use AppBundle\Entity\User;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class SecurityContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var SecurityServiceInterface
     */
    private $securityService;

    /**
     * @var ExampleFactoryInterface
     */
    private $userFactory;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param SecurityServiceInterface $securityService
     * @param ExampleFactoryInterface $userFactory
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        SecurityServiceInterface $securityService,
        ExampleFactoryInterface $userFactory,
        UserRepositoryInterface $userRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->securityService = $securityService;
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    /**
     * @Given I am logged in as :email
     */
    public function iAmLoggedInAs($email)
    {
        $user = $this->userRepository->findOneByEmail($email);
        Assert::notNull($user);

        $this->securityService->logIn($user);
    }

    /**
     * @Given I am a logged in customer
     */
    public function iAmLoggedInAsACustomer()
    {
        /** @var User $user */
        $user = $this->userFactory->create(['email' => 'customer@example.com', 'password' => 'password', 'roles' => ['ROLE_USER']]);
        $this->userRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('customer', $user->getCustomer());
    }

    /**
     * @Given I am a logged in administrator
     */
    public function iAmLoggedInAsAnAdministrator()
    {
        /** @var UserInterface $user */
        $user = $this->userFactory->create(['email' => 'admin@example.com', 'password' => 'admin', 'roles' => ['ROLE_ADMIN']]);
        $this->userRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('administrator', $user);
    }

    /**
     * @Given /^I am logged in as "([^"]+)" administrator$/
     */
    public function iAmLoggedInAsAdministrator($email)
    {
        $user = $this->userRepository->findOneByEmail($email);
        Assert::notNull($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('administrator', $user);
    }

    /**
     * @Given I am a logged in redactor
     */
    public function iAmLoggedInAsARedactor()
    {
        /** @var UserInterface $user */
        $user = $this->userFactory->create(['email' => 'redactor@example.com', 'password' => 'password123', 'roles' => ['ROLE_REDACTOR']]);
        $this->userRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('redactor', $user);
    }

    /**
     * @Given I am a logged in staff user
     */
    public function iAmLoggedInAsAStaffUser()
    {
        /** @var UserInterface $user */
        $user = $this->userFactory->create(['email' => 'staff-user@example.com', 'password' => 'password123', 'roles' => ['ROLE_STAFF']]);
        $this->userRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('staff', $user);
    }

    /**
     * @Given I am a logged in product manager
     */
    public function iAmLoggedInAsAProductManager()
    {
        /** @var UserInterface $user */
        $user = $this->userFactory->create(['email' => 'product-manager@example.com', 'password' => 'password123', 'roles' => ['ROLE_PRODUCT_MANAGER']]);
        $this->userRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('product_manager', $user);
    }

    /**
     * @Given I am a logged in article manager
     */
    public function iAmLoggedInAsAnArticleManager()
    {
        /** @var UserInterface $user */
        $user = $this->userFactory->create(['email' => 'article-manager@example.com', 'password' => 'password123', 'roles' => ['ROLE_ARTICLE_MANAGER']]);
        $this->userRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('article_manager', $user);
    }
}
