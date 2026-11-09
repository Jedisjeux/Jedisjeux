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

namespace App\Tests\Behat\Context\Ui\Backend;

use App\Tests\Behat\Page\Backend\CustomerGroup\CreatePage;
use App\Tests\Behat\Page\Backend\CustomerGroup\IndexPage;
use App\Tests\Behat\Page\Backend\CustomerGroup\UpdatePage;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Webmozart\Assert\Assert;

final class ManagingCustomerGroupsContext implements Context
{
    public function __construct(
        private readonly IndexPage $indexPage,
        private readonly CreatePage $createPage,
        private readonly UpdatePage $updatePage,
        #[Autowire(service: 'sylius.repository.customer_group')]
        private readonly RepositoryInterface $repository,
    ) {
    }

    /**
     * @When I want to browse customer groups
     */
    public function iWantToBrowse(): void
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to create a new customer group
     */
    public function iWantToCreateANew(): void
    {
        $this->createPage->open();
    }

    /**
     * @Given I want to edit the customer group :name
     */
    public function iWantToEdit(string $name): void
    {
        $this->updatePage->open(['id' => $this->repository->findOneBy(['name' => $name])->getId()]);
    }

    /**
     * @When I specify its code as :code
     */
    public function iSpecifyItsCodeAs(string $code): void
    {
        $this->createPage->specifyCode($code);
    }

    /**
     * @When I name it :name
     */
    public function iNameIt(string $name): void
    {
        $this->createPage->nameIt($name);
    }

    /**
     * @When I rename it to :name
     */
    public function iRenameItTo(string $name): void
    {
        $this->updatePage->nameIt($name);
    }

    /**
     * @When I add it
     */
    public function iAddIt(): void
    {
        $this->createPage->create();
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges(): void
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I delete the customer group :name
     */
    public function iDelete(string $name): void
    {
        $this->indexPage->deleteResourceOnPage(['name' => $name]);
    }

    /**
     * @Then /^there should be (\d+) customer groups in the list$/
     */
    public function thereShouldBeInTheList(int $number): void
    {
        Assert::same($this->indexPage->countItems(), $number);
    }

    /**
     * @Then I should see the customer group :name in the list
     */
    public function iShouldSeeInTheList(string $name): void
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then there should not be the customer group :name anymore
     */
    public function thereShouldNotBeAnymore(string $name): void
    {
        $this->indexPage->open();

        Assert::false($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }
}
