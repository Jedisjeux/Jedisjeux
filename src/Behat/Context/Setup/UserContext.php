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

namespace App\Behat\Context\Setup;

use App\Fixture\Factory\AppUserExampleFactory;
use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use App\Behat\Service\SharedStorageInterface;
use App\Entity\User;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;

final class UserContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var UserRepositoryInterface
     */
    private $appUserRepository;

    /**
     * @var AppUserExampleFactory
     */
    private $appUserFactory;

    /**
     * @var ObjectManager
     */
    private $appUserManager;

    /**
     * @param SharedStorageInterface  $sharedStorage
     * @param UserRepositoryInterface $appUserRepository
     * @param AppUserExampleFactory   $appUserFactory
     * @param ObjectManager           $appUserManager
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        UserRepositoryInterface $appUserRepository,
        AppUserExampleFactory $appUserFactory,
        ObjectManager $appUserManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->appUserRepository = $appUserRepository;
        $this->appUserFactory = $appUserFactory;
        $this->appUserManager = $appUserManager;
    }

    /**
     * @Given there is a user :email identified by :password
     * @Given there was account of :email with password :password
     * @Given there is (also )a user :email
     */
    public function thereIsUserIdentifiedBy($email, $password = 'sylius')
    {
        $user = $this->appUserFactory->create(['email' => $email, 'password' => $password, 'enabled' => true]);

        $this->sharedStorage->set('user', $user);
        $this->sharedStorage->set('customer', $user->getCustomer());

        $this->appUserRepository->add($user);
    }

    /**
     * @Given there is (also )a user with username :username
     * @Given there is (also )a user with username :username identified by :password
     */
    public function thereIsUserWithUsername(string $username, $password = 'sylius')
    {
        $user = $this->appUserFactory->create(['username' => $username, 'password' => $password, 'enabled' => true]);

        $this->sharedStorage->set('user', $user);
        $this->sharedStorage->set('customer', $user->getCustomer());

        $this->appUserRepository->add($user);
    }

    /**
     * @Given /^there is a (reviewer|translator|publisher) "([^"]*)"$/
     */
    public function thereIsAReviewer($role, $email, $password = 'sylius')
    {
        /** @var UserInterface $user */
        $user = $this->appUserFactory->create([
            'email' => $email,
            'password' => $password,
            'enabled' => true,
        ]);

        if ('reviewer' === $role) {
            $user->addRole('ROLE_REVIEWER');
        } elseif ('translator' === $role) {
            $user->addRole('ROLE_TRANSLATOR');
        } elseif ('publisher' === $role) {
            $user->addRole('ROLE_PUBLISHER');
        }

        $this->sharedStorage->set('user', $user);

        $this->appUserRepository->add($user);
    }

    /**
     * @Given the account of :email was deleted
     * @Given my account :email was deleted
     */
    public function accountWasDeleted($email)
    {
        /** @var User $user */
        $user = $this->appUserRepository->findOneByEmail($email);

        $this->sharedStorage->set('customer', $user->getCustomer());

        $this->appUserRepository->remove($user);
    }

    /**
     * @Given its account was deleted
     */
    public function hisAccountWasDeleted()
    {
        $user = $this->sharedStorage->get('user');

        $this->appUserRepository->remove($user);
    }

    /**
     * @Given /^(this user) is not verified$/
     * @Given /^(I) have not verified my account (?:yet)$/
     */
    public function accountIsNotVerified(UserInterface $user)
    {
        $user->setVerifiedAt(null);

        $this->appUserManager->flush();
    }

    /**
     * @Given /^(?:(I) have|(this user) has) already received a verification email$/
     */
    public function iHaveReceivedVerificationEmail(UserInterface $user)
    {
        $this->prepareUserVerification($user);
    }

    /**
     * @Given a verification email has already been sent to :email
     */
    public function aVerificationEmailHasBeenSentTo($email)
    {
        $user = $this->appUserRepository->findOneByEmail($email);

        $this->prepareUserVerification($user);
    }

    /**
     * @Given /^(I) have already verified my account$/
     */
    public function iHaveAlreadyVerifiedMyAccount(UserInterface $user)
    {
        $user->setVerifiedAt(new \DateTime());

        $this->appUserManager->flush();
    }

    /**
     * @Given /^(?:(I) have|(this user) has) already received a resetting password email$/
     */
    public function iHaveReceivedResettingPasswordEmail(UserInterface $user)
    {
        $this->prepareUserPasswordResetToken($user);
    }

    /**
     * @param UserInterface $user
     */
    private function prepareUserVerification(UserInterface $user)
    {
        $token = 'marryhadalittlelamb';
        $this->sharedStorage->set('verification_token', $token);

        $user->setEmailVerificationToken($token);

        $this->appUserManager->flush();
    }

    /**
     * @param UserInterface $user
     */
    private function prepareUserPasswordResetToken(UserInterface $user)
    {
        $token = 'itotallyforgotmypassword';

        $user->setPasswordResetToken($token);
        $user->setPasswordRequestedAt(new \DateTime());

        $this->appUserManager->flush();
    }
}
