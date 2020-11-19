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

use App\Tests\Behat\Page\Backend\ProductList\IndexPage;
use App\Tests\Behat\Page\Backend\ProductList\UpdatePage;
use App\Entity\ProductList;
use Behat\Behat\Context\Context;
use Monofony\Bridge\Behat\Service\Resolver\CurrentPageResolverInterface;
use Webmozart\Assert\Assert;

final class ManagingProductListsContext implements Context
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
     * @When I want to browse product lists
     */
    public function iWantToBrowseProductLists()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to edit :productList product list
     */
    public function iWantToEditTheProductList(ProductList $productList)
    {
        $this->updatePage->open(['id' => $productList->getId()]);
    }

    /**
     * @When I change its name as :name
     */
    public function iChangeItsTitleAs($name)
    {
        $this->updatePage->nameIt($name);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I delete the :name product list
     */
    public function iDeleteProductListWithTitle($name)
    {
        $this->iWantToBrowseProductLists();
        $this->indexPage->deleteResourceOnPage(['name' => $name]);
    }

    /**
     * @Then /^there should be (\d+) product lists in the list$/
     */
    public function iShouldSeeProductListsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then I should (also) see the product list :name in the list
     */
    public function iShouldSeeTheProductListTitleInTheList($name)
    {
        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then this product list with name :name should appear in the website
     */
    public function thisProductListWithTitleShouldAppearInTheWebsite($name)
    {
        $this->iWantToBrowseProductLists();
        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then this product list body should be :body
     */
    public function thisProductListBodyShouldBe($body)
    {
        $this->assertElementValue('body', $body);
    }

    /**
     * @Then there should not be :name product list anymore
     */
    public function thereShouldBeNoProductListAnymore($name)
    {
        $this->iWantToBrowseProductLists();
        Assert::false($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @param string $element
     * @param string $value
     */
    private function assertElementValue($element, $value)
    {
        Assert::true(
            $this->updatePage->hasResourceValues([$element => $value]),
            sprintf('ProductList should have %s with %s value.', $element, $value)
        );
    }
}
