<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Backend;

use App\Tests\Behat\Page\Backend\Product\CreatePageFromBgg;
use App\Tests\Behat\Page\Backend\Product\IndexPage;
use App\Tests\Behat\Page\Backend\Product\UpdatePage;
use App\Tests\Behat\Page\Backend\Product\CreatePage;
use App\Entity\GameAward;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;
use Behat\Behat\Context\Context;
use Monofony\Bridge\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Webmozart\Assert\Assert;

final class ManagingProductsContext implements Context
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
     * @var CreatePageFromBgg
     */
    private $createFromBggPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @var CurrentPageResolverInterface
     */
    private $currentPageResolver;

    public function __construct(
        IndexPage $indexPage,
        CreatePage $createPage,
        CreatePageFromBgg $createFromBggPage,
        UpdatePage $updatePage,
        CurrentPageResolverInterface $currentPageResolver
    ) {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->createFromBggPage = $createFromBggPage;
        $this->updatePage = $updatePage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @Given I want to create a new product
     * @Given I want to create a new product via bgg url :bggPath
     */
    public function iWantToCreateANewProduct(string $bggPath = null)
    {
        if (null !== $bggPath) {
            $this->createFromBggPage->open([
                'bggPath' => $bggPath,
            ]);

            return;
        }

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
        $this->createPage->nameIt($name);
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
     * @When I attach the :path image
     */
    public function iAttachImage($path)
    {
        $currentPage = $this->resolveCurrentPage();

        $currentPage->attachImage($path);
    }

    /**
     * @When I change its name as :name
     */
    public function iChangeItsNameAs($name)
    {
        $this->updatePage->nameIt($name);
    }

    /**
     * @When I add a new video :path titled :title
     */
    public function iAddVideoTitled($path = null, $title = null)
    {
        $this->createPage->addVideo($path, $title);
    }

    /**
     * @When I add a new award :gameAward :year
     */
    public function iAddAward(GameAward $gameAward, string $year)
    {
        $this->createPage->addAward($gameAward, $year);
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
     * @When I ask for a translation
     */
    public function iAskForTranslation()
    {
        $this->updatePage->askForTranslation();
    }

    /**
     * @When I ask for a review
     */
    public function iAskForReview()
    {
        $this->updatePage->askForReview();
    }

    /**
     * @When I ask for a publication
     */
    public function iAskForPublication()
    {
        $this->updatePage->askForPublication();
    }

    /**
     * @When I publish it
     */
    public function iPublishIt()
    {
        $this->updatePage->publish();
    }

    /**
     * @Then I should be notified that the name is required
     */
    public function iShouldBeNotifiedThatNameIsRequired()
    {
        Assert::same($this->createPage->getValidationMessage('name'), 'Please enter product name.');
    }

    /**
     * @Then I should be notified that min player count value should not be greater than max value
     */
    public function iShouldBeNotifiedThatMinPlayerCountValueShouldNotBeGreaterThenMaxValueIsRequired()
    {
        Assert::same($this->createPage->getValidationMessage('min_player_count'), 'Min value should not be greater than max value.');
    }

    /**
     * @Then I should be notified that min player count value should be one or more
     */
    public function iShouldBeNotifiedThatMinPlayerCountValueShouldBeOneOrMore()
    {
        Assert::same($this->createPage->getValidationMessage('min_player_count'), 'This value should be 1 or more.');
    }

    /**
     * @Then I should be notified that max player count value should be one or more
     */
    public function iShouldBeNotifiedThatMaxPlayerCountValueShouldBeOneOrMore()
    {
        Assert::same($this->createPage->getValidationMessage('max_player_count'), 'This value should be 1 or more.');
    }

    /**
     * @Then /^there should be (\d+) products in the list$/
     */
    public function iShouldSeeProductsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
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
     * @Then this product with name :name should have :status status
     */
    public function thisProductWithNameShouldHaveStatus($name, $status)
    {
        $this->indexPage->open();

        $status = ucfirst($status);

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name, 'status' => $status]));
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

    /**
     * @Then the product :productName should have one single image
     */
    public function theProductShouldHaveImagesCount(string $productName, $imageCount = 1)
    {
        Assert::eq($this->updatePage->countImages(), $imageCount);
    }

    /**
     * @return CreatePage|UpdatePage
     */
    private function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->createPage,
            $this->updatePage,
        ]);
    }
}
