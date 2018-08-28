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

namespace AppBundle\Behat\Context\Ui\Frontend;

use Behat\Behat\Context\Context;
use AppBundle\Behat\NotificationType;
use AppBundle\Behat\Page\Frontend\Account\LoginPage;
use AppBundle\Behat\Page\Frontend\Account\RegisterPage;
use AppBundle\Behat\Page\Frontend\Account\ResetPasswordPage;
use AppBundle\Behat\Page\Frontend\HomePage;
use AppBundle\Behat\Service\NotificationCheckerInterface;
use Webmozart\Assert\Assert;

final class LoginContext implements Context
{
    /**
     * @var HomePage
     */
    private $homePage;

    /**
     * @var LoginPage
     */
    private $loginPage;

    /**
     * @var RegisterPage
     */
    private $registerPage;

    /**
     * @var ResetPasswordPage
     */
    private $resetPasswordPage;

    /**
     * @var NotificationCheckerInterface
     */
    private $notificationChecker;

    /**
     * @param HomePage $homePage
     * @param LoginPage $loginPage
     * @param RegisterPage $registerPage
     * @param ResetPasswordPage $resetPasswordPage
     * @param NotificationCheckerInterface $notificationChecker
     */
    public function __construct(
        HomePage $homePage,
        LoginPage $loginPage,
        RegisterPage $registerPage,
        ResetPasswordPage $resetPasswordPage,
        NotificationCheckerInterface $notificationChecker
    ) {
        $this->homePage = $homePage;
        $this->loginPage = $loginPage;
        $this->registerPage = $registerPage;
        $this->resetPasswordPage = $resetPasswordPage;
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @When I want to log in
     */
    public function iWantToLogIn()
    {
        $this->loginPage->open();
    }

    /**
     * @When I want to reset password
     */
    public function iWantToResetPassword()
    {
        $this->resetPasswordPage->open();
    }

    /**
     * @When I specify the username as :username
     */
    public function iSpecifyTheUsername($username = null)
    {
        $this->loginPage->specifyUsername($username);
    }

    /**
     * @When I specify the email as :email
     * @When I do not specify the email
     */
    public function iSpecifyTheEmail($email = null)
    {
        $this->resetPasswordPage->specifyEmail($email);
    }

    /**
     * @When I specify the password as :password
     * @When I do not specify the password
     */
    public function iSpecifyThePasswordAs($password = null)
    {
        $this->loginPage->specifyPassword($password);
    }

    /**
     * @When I log in
     * @When I try to log in
     */
    public function iLogIn()
    {
        $this->loginPage->logIn();
    }

    /**
     * @When I reset it
     * @When I try to reset it
     */
    public function iResetIt()
    {
        $this->resetPasswordPage->reset();
    }

    /**
     * @When I sign in with email :email and password :password
     */
    public function iSignInWithEmailAndPassword(string $email, string $password): void
    {
        $this->iWantToLogIn();
        $this->iSpecifyTheUsername($email);
        $this->iSpecifyThePasswordAs($password);
        $this->iLogIn();
    }

    /**
     * @When I register with email :email and password :password
     */
    public function iRegisterWithEmailAndPassword($email, $password)
    {
        $this->registerPage->open();
        $this->registerPage->specifyEmail($email);
        $this->registerPage->specifyPassword($password);
        $this->registerPage->verifyPassword($password);
        $this->registerPage->specifyFirstName('Carrot');
        $this->registerPage->specifyLastName('Ironfoundersson');
        $this->registerPage->register();
    }

    /**
     * @Then I should be logged in
     */
    public function iShouldBeLoggedIn()
    {
        $this->homePage->verify();
        Assert::true($this->homePage->hasLogoutButton());
    }

    /**
     * @Then I should not be logged in
     */
    public function iShouldNotBeLoggedIn()
    {
        Assert::false($this->homePage->hasLogoutButton());
    }

    /**
     * @Then I should be notified about bad credentials
     */
    public function iShouldBeNotifiedAboutBadCredentials()
    {
        Assert::true($this->loginPage->hasValidationErrorWith('Error Invalid credentials.'));
    }

    /**
     * @Then I should be notified about disabled account
     */
    public function iShouldBeNotifiedAboutDisabledAccount()
    {
        Assert::true($this->loginPage->hasValidationErrorWith('Error Account is disabled.'));
    }

    /**
     * @Then I should be notified that email with reset instruction has been send
     */
    public function iShouldBeNotifiedThatEmailWithResetInstructionWasSend()
    {
        $this->notificationChecker->checkNotification('If the email you have specified exists in our system, we have sent there an instruction on how to reset your password.', NotificationType::success());
    }

    /**
     * @Then I should be notified that the :elementName is required
     */
    public function iShouldBeNotifiedThatElementIsRequired($elementName)
    {
        Assert::true($this->resetPasswordPage->checkValidationMessageFor($elementName, sprintf('Please enter your %s.', $elementName)));
    }

    /**
     * @Then I should be able to log in as :email with :password password
     * @Then the customer should be able to log in as :email with :password password
     */
    public function iShouldBeAbleToLogInAsWithPassword($email, $password)
    {
        $this->loginPage->open();
        $this->loginPage->specifyUsername($email);
        $this->loginPage->specifyPassword($password);
        $this->loginPage->logIn();

        $this->iShouldBeLoggedIn();
    }
}
