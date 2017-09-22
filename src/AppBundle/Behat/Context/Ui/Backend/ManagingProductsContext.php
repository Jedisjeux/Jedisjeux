<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Ui\Backend;

use AppBundle\Behat\Page\Backend\Product\IndexPage;
use AppBundle\Behat\Page\Backend\Product\UpdatePage;
use AppBundle\Behat\Page\Backend\Product\CreatePage;
use AppBundle\Behat\Page\UnexpectedPageException;
use AppBundle\Behat\Service\Resolver\CurrentPageResolverInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingProductsContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var CreatePage
     */
    private $createPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @var CurrentPageResolverInterface
     */
    private $currentPageResolver;

    /**
     * ManagingPeopleContext constructor.
     *
     * @param IndexPage $indexPage
     * @param CreatePage $createPage
     * @param UpdatePage $updatePage
     * @param CurrentPageResolverInterface $currentPageResolver
     */
    public function __construct(
        IndexPage $indexPage,
        CreatePage $createPage,
        UpdatePage $updatePage,
        CurrentPageResolverInterface $currentPageResolver)
    {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @Given I want to create a new product
     */
    public function iWantToCreateANewProduct()
    {
        $this->createPage->open();
    }

    /**
     * @When I want to browse products
     */
    public function iWantToBrowseProducts()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to edit :product product
     */
    public function iWantToEditTheProduct(ProductInterface $product)
    {
        $this->updatePage->open(['id' => $product->getId()]);
    }

    /**
     * @When /^I specify (?:their|his) name as "([^"]*)"$/
     * @When I do not specify its name
     */
    public function iSpecifyItsNameAs($name = null)
    {
        $this->createPage->specifyName($name);
    }

    /**
     * @When /^I specify (?:their|his) slug as "([^"]*)"$/
     * @When I do not specify its slug
     */
    public function iSpecifyItsSlugAs($slug = null)
    {
        $this->createPage->specifySlug($slug);
    }

    /**
     * @When /^I specify (?:their|his) min player count as "([^"]*)"$/
     * @When I do not specify its min player count
     */
    public function iSpecifyItsMinPlayerCountAs($minPlayerCount = null)
    {
        $this->createPage->specifyMinPlayerCount($minPlayerCount);
    }

    /**
     * @When /^I specify (?:their|his) max player count as "([^"]*)"$/
     * @When I do not specify its max player count
     */
    public function iSpecifyItsMaxPlayerCountAs($maxPlayerCount = null)
    {
        $this->createPage->specifyMaxPlayerCount($maxPlayerCount);
    }

    /**
     * @When I change its name as :name
     */
    public function iChangeItsNameAs($name)
    {
        $this->updatePage->changeName($name);
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt()
    {
        $this->createPage->create();
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I delete product with name :name
     */
    public function iDeleteProductWithName($name)
    {
        $this->indexPage->deleteResourceOnPage(['name' => $name]);
    }

    /**
     * @Then I should be notified that the name is required
     */
    public function iShouldBeNotifiedThatNameIsRequired()
    {
        Assert::same($this->createPage->getValidationMessage('name'), 'This value should not be blank.');
    }

    /**
     * @Then /^there should be (\d+) products in the list$/
     */
    public function iShouldSeeProductsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int)$number);
    }

    /**
     * @Then this product should not be added
     */
    public function thisProductShouldNotBeAdded()
    {
        $this->indexPage->open();

        Assert::same($this->indexPage->countItems(), 0);
    }

    /**
     * @Then the product :product should appear in the website
     * @Then I should see the product :product in the list
     */
    public function theProductShould(ProductInterface $product)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $product->getName()]));
    }

    /**
     * @Then this product with name :name should appear in the website
     */
    public function thisProductWithNameShouldAppearInTheStore($name)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then there should not be :name product anymore
     */
    public function thereShouldBeNoAnymore($name)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then I should not be able to add product
     */
    public function iShouldNotBeAbleToAddProduct()
    {
        try {
            $this->createPage->open();

        } catch (UnexpectedPageException $exception) {
            // nothing else to do
        }

        Assert::false($this->indexPage->isOpen());
    }

    /**
     * @Then I should not be able to browse products
     */
    public function iShouldNotBeAbleToBrowseProducts()
    {
        try {
            $this->indexPage->open();

        } catch (UnexpectedPageException $exception) {
            // nothing else to do
        }

        Assert::false($this->indexPage->isOpen());
    }
}
