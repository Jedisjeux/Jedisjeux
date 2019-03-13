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

use App\Behat\Service\SecurityServiceInterface;
use App\Behat\Service\SharedStorageInterface;
use App\Entity\User;
use App\Fixture\Factory\AppUserExampleFactory;
use Behat\Behat\Context\Context;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Webmozart\Assert\Assert;

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
     * @var AppUserExampleFactory
     */
    private $appUserFactory;

    /**
     * @var UserRepositoryInterface
     */
    private $appUserRepository;

    /**
     * @param SharedStorageInterface   $sharedStorage
     * @param SecurityServiceInterface $securityService
     * @param AppUserExampleFactory    $appUserFactory
     * @param UserRepositoryInterface  $appUserRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        SecurityServiceInterface $securityService,
        AppUserExampleFactory $appUserFactory,
        UserRepositoryInterface $appUserRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->securityService = $securityService;
        $this->appUserFactory = $appUserFactory;
        $this->appUserRepository = $appUserRepository;
    }

    /**
     * @Given I am logged in as :email
     */
    public function iAmLoggedInAs($email)
    {
        $user = $this->appUserRepository->findOneByEmail($email);
        Assert::notNull($user);

        $this->securityService->logIn($user);
    }

    /**
     * @Given I am a logged in customer
     */
    public function iAmLoggedInAsACustomer()
    {
        /** @var User $user */
        $user = $this->appUserFactory->create(['email' => 'customer@example.com', 'password' => 'password', 'roles' => ['ROLE_USER']]);
        $this->appUserRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('customer', $user->getCustomer());
    }

    /**
     * @Given I am a logged in administrator
     */
    public function iAmLoggedInAsAnAdministrator()
    {
        /** @var UserInterface $user */
        $user = $this->appUserFactory->create(['email' => 'admin@example.com', 'password' => 'admin', 'roles' => ['ROLE_ADMIN']]);
        $this->appUserRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('customer', $user->getCustomer());
        $this->sharedStorage->set('administrator', $user);
    }

    /**
     * @Given /^I am logged in as "([^"]+)" administrator$/
     */
    public function iAmLoggedInAsAdministrator($email)
    {
        $user = $this->appUserRepository->findOneByEmail($email);
        Assert::notNull($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('customer', $user->getCustomer());
        $this->sharedStorage->set('administrator', $user);
    }

    /**
     * @Given I am a logged in redactor
     */
    public function iAmLoggedInAsARedactor()
    {
        /** @var User $user */
        $user = $this->appUserFactory->create(['email' => 'redactor@example.com', 'password' => 'password123', 'roles' => ['ROLE_REDACTOR']]);
        $this->appUserRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('customer', $user->getCustomer());
        $this->sharedStorage->set('redactor', $user);
    }

    /**
     * @Given I am a logged in reviewer
     */
    public function iAmLoggedInAsAReviewer()
    {
        /** @var User $user */
        $user = $this->appUserFactory->create(['email' => 'reviewer@example.com', 'password' => 'password123', 'roles' => ['ROLE_REVIEWER']]);
        $this->appUserRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('customer', $user->getCustomer());
        $this->sharedStorage->set('reviewer', $user);
    }

    /**
     * @Given I am a logged in publisher
     */
    public function iAmLoggedInAsAPublisher()
    {
        /** @var User $user */
        $user = $this->appUserFactory->create(['email' => 'publisher@example.com', 'password' => 'password123', 'roles' => ['ROLE_PUBLISHER']]);
        $this->appUserRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('customer', $user->getCustomer());
        $this->sharedStorage->set('publisher', $user);
    }

    /**
     * @Given I am a logged in staff user
     */
    public function iAmLoggedInAsAStaffUser()
    {
        /** @var UserInterface $user */
        $user = $this->appUserFactory->create(['email' => 'staff-user@example.com', 'password' => 'password123', 'roles' => ['ROLE_STAFF']]);
        $this->appUserRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('customer', $user->getCustomer());
        $this->sharedStorage->set('staff', $user);
    }

    /**
     * @Given I am a logged in product manager
     */
    public function iAmLoggedInAsAProductManager()
    {
        /** @var UserInterface $user */
        $user = $this->appUserFactory->create(['email' => 'product-manager@example.com', 'password' => 'password123', 'roles' => ['ROLE_PRODUCT_MANAGER']]);
        $this->appUserRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('customer', $user->getCustomer());
        $this->sharedStorage->set('product_manager', $user);
    }

    /**
     * @Given I am a logged in article manager
     */
    public function iAmLoggedInAsAnArticleManager()
    {
        /** @var UserInterface $user */
        $user = $this->appUserFactory->create(['email' => 'article-manager@example.com', 'password' => 'password123', 'roles' => ['ROLE_ARTICLE_MANAGER']]);
        $this->appUserRepository->add($user);

        $this->securityService->logIn($user);

        $this->sharedStorage->set('customer', $user->getCustomer());
        $this->sharedStorage->set('article_manager', $user);
    }
}
