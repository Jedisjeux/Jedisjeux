<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Behat\Context\Ui\Backend;

use Behat\Behat\Context\Context;
use App\Behat\Page\Backend\Customer\IndexPage;
use App\Behat\Page\Backend\Customer\UpdatePage;
use App\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Webmozart\Assert\Assert;

final class ManagingCustomersContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @var CurrentPageResolverInterface
     */
    private $currentPageResolver;

    /**
     * @param IndexPage                    $indexPage
     * @param UpdatePage                   $updatePage
     * @param CurrentPageResolverInterface $currentPageResolver
     */
    public function __construct(
        IndexPage $indexPage,
        UpdatePage $updatePage,
        CurrentPageResolverInterface $currentPageResolver
    ) {
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @When I change their email to :email
     * @When I remove its email
     */
    public function iChangeTheirEmailTo(?string $email = null): void
    {
        $this->updatePage->changeEmail($email);
    }

    /**
     * @Then the customer :customer should appear in the store
     * @Then the customer :customer should still have this email
     */
    public function theCustomerShould(CustomerInterface $customer)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['email' => $customer->getEmail()]));
    }

    /**
     * @When /^I want to edit (this customer)$/
     */
    public function iWantToEditThisCustomer(CustomerInterface $customer)
    {
        $this->updatePage->open(['id' => $customer->getId()]);
    }

    /**
     * @When I save my changes
     * @When I try to save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @Then /^(this customer) with email "([^"]*)" should appear in the store$/
     */
    public function theCustomerWithEmailShouldAppearInTheRegistry(CustomerInterface $customer, $email)
    {
        $this->updatePage->open(['id' => $customer->getId()]);

        Assert::same($this->updatePage->getEmail(), $email);
    }

    /**
     * @When I want to browse customers
     */
    public function iWantToBrowseCustomers()
    {
        $this->indexPage->open();
    }

    /**
     * @Then /^I should see (\d+) customers in the list$/
     */
    public function iShouldSeeCustomersInTheList($amountOfCustomers)
    {
        Assert::same($this->indexPage->countItems(), (int) $amountOfCustomers);
    }

    /**
     * @Then I should see the customer :email in the list
     */
    public function iShouldSeeTheCustomerInTheList($email)
    {
        Assert::true($this->indexPage->isSingleResourceOnPage(['email' => $email]));
    }

    /**
     * @Then /^I should be notified that ([^"]+) should be ([^"]+)$/
     */
    public function iShouldBeNotifiedThatTheElementShouldBe($elementName, $validationMessage)
    {
        Assert::same(
            $this->updatePage->getValidationMessage($elementName),
            sprintf('%s must be %s.', ucfirst($elementName), $validationMessage)
        );
    }

    /**
     * @Then the customer with email :email should not appear in the store
     */
    public function theCustomerShouldNotAppearInTheStore($email)
    {
        $this->indexPage->open();

        Assert::false($this->indexPage->isSingleResourceOnPage(['email' => $email]));
    }

    /**
     * @Then there should still be only one customer with email :email
     */
    public function thereShouldStillBeOnlyOneCustomerWithEmail($email)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['email' => $email]));
    }

    /**
     * @Given I want to enable :customer
     * @Given I want to disable :customer
     */
    public function iWantToChangeStatusOf(CustomerInterface $customer)
    {
        $this->updatePage->open(['id' => $customer->getId()]);
    }

    /**
     * @When I enable their account
     */
    public function iEnableIt()
    {
        $this->updatePage->enable();
    }

    /**
     * @When I disable their account
     */
    public function iDisableIt()
    {
        $this->updatePage->disable();
    }

    /**
     * @Then /^(this customer) should be enabled$/
     */
    public function thisCustomerShouldBeEnabled(CustomerInterface $customer)
    {
        $this->indexPage->open();

        Assert::eq($this->indexPage->getCustomerAccountStatus($customer), 'Enabled');
    }

    /**
     * @Then /^(this customer) should be disabled$/
     */
    public function thisCustomerShouldBeDisabled(CustomerInterface $customer)
    {
        $this->indexPage->open();

        Assert::eq($this->indexPage->getCustomerAccountStatus($customer), 'Disabled');
    }

    /**
     * @Then the customer :customer should have an account created
     * @Then /^(this customer) should have an account created$/
     */
    public function theyShouldHaveAnAccountCreated(CustomerInterface $customer)
    {
        Assert::notNull(
            $customer->getUser()->getPassword(),
            'Customer should have an account, but they do not.'
        );
    }

    /**
     * @When I change the password of user :customer to :newPassword
     */
    public function iChangeThePasswordOfUserTo(CustomerInterface $customer, $newPassword)
    {
        $this->updatePage->open(['id' => $customer->getId()]);
        $this->updatePage->changePassword($newPassword);
        $this->updatePage->saveChanges();
    }

    /**
     * @When I do not specify any information
     */
    public function iDoNotSpecifyAnyInformation()
    {
        // Intentionally left blank.
    }

    /**
     * @When I do not choose create account option
     */
    public function iDoNotChooseCreateAccountOption()
    {
        // Intentionally left blank.
    }
}
