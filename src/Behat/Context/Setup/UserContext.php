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

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use App\Behat\Service\SharedStorageInterface;
use App\Fixture\Factory\ExampleFactoryInterface;
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
    private $userRepository;

    /**
     * @var ExampleFactoryInterface
     */
    private $userFactory;

    /**
     * @var ObjectManager
     */
    private $userManager;

    /**
     * @param SharedStorageInterface  $sharedStorage
     * @param UserRepositoryInterface $userRepository
     * @param ExampleFactoryInterface $userFactory
     * @param ObjectManager           $userManager
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        UserRepositoryInterface $userRepository,
        ExampleFactoryInterface $userFactory,
        ObjectManager $userManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->userManager = $userManager;
    }

    /**
     * @Given there is a user :email identified by :password
     * @Given there was account of :email with password :password
     * @Given there is a user :email
     */
    public function thereIsUserIdentifiedBy($email, $password = 'sylius')
    {
        $user = $this->userFactory->create(['email' => $email, 'password' => $password, 'enabled' => true]);

        $this->sharedStorage->set('user', $user);

        $this->userRepository->add($user);
    }

    /**
     * @Given /^there is a (reviewer|translator|publisher) "([^"]*)"$/
     */
    public function thereIsAReviewer($role, $email, $password = 'sylius')
    {
        /** @var UserInterface $user */
        $user = $this->userFactory->create([
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

        $this->userRepository->add($user);
    }

    /**
     * @Given the account of :email was deleted
     * @Given my account :email was deleted
     */
    public function accountWasDeleted($email)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneByEmail($email);

        $this->sharedStorage->set('customer', $user->getCustomer());

        $this->userRepository->remove($user);
    }

    /**
     * @Given its account was deleted
     */
    public function hisAccountWasDeleted()
    {
        $user = $this->sharedStorage->get('user');

        $this->userRepository->remove($user);
    }

    /**
     * @Given /^(this user) is not verified$/
     * @Given /^(I) have not verified my account (?:yet)$/
     */
    public function accountIsNotVerified(UserInterface $user)
    {
        $user->setVerifiedAt(null);

        $this->userManager->flush();
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
        $user = $this->userRepository->findOneByEmail($email);

        $this->prepareUserVerification($user);
    }

    /**
     * @Given /^(I) have already verified my account$/
     */
    public function iHaveAlreadyVerifiedMyAccount(UserInterface $user)
    {
        $user->setVerifiedAt(new \DateTime());

        $this->userManager->flush();
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

        $this->userManager->flush();
    }

    /**
     * @param UserInterface $user
     */
    private function prepareUserPasswordResetToken(UserInterface $user)
    {
        $token = 'itotallyforgotmypassword';

        $user->setPasswordResetToken($token);
        $user->setPasswordRequestedAt(new \DateTime());

        $this->userManager->flush();
    }
}
