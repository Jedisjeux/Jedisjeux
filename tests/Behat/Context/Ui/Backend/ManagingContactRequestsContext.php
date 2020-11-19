<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Backend;

use App\Tests\Behat\Page\Backend\ContactRequest\IndexPage;
use App\Tests\Behat\Page\Backend\ContactRequest\ShowPage;
use App\Entity\ContactRequest;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

final class ManagingContactRequestsContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var ShowPage
     */
    private $showPage;

    public function __construct(IndexPage $indexPage, ShowPage $showPage)
    {
        $this->indexPage = $indexPage;
        $this->showPage = $showPage;
    }

    /**
     * @When I want to browse contact requests
     */
    public function iWantToBrowseContactRequests()
    {
        $this->indexPage->open();
    }

    /**
     * @When /^I check (this contact request)'s details$/
     */
    public function iOpenProductPage(ContactRequest $contactRequest)
    {
        $this->showPage->open(['id' => $contactRequest->getId()]);
    }

    /**
     * @When I delete contact request with email :email
     */
    public function iDeleteContactRequestWithEmail($email)
    {
        $this->indexPage->deleteResourceOnPage(['email' => $email]);
    }

    /**
     * @Then /^there should be (\d+) contact requests in the list$/
     */
    public function iShouldSeeContactRequestsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then I should see the contact request from :email in the list
     */
    public function theContactRequestShould(string $email)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['email' => $email]));
    }

    /**
     * @Then there should not be contact request from :email anymore
     */
    public function thereShouldBeNoAnymore($email)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['email' => $email]));
    }

    /**
     * @Then I should see the contact request email :email
     */
    public function iShouldSeeContactRequestEmail($email)
    {
        Assert::same($this->showPage->getEmail(), $email);
    }
}
