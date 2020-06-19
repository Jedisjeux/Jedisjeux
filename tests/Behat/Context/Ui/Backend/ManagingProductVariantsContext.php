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

use App\Tests\Behat\Page\Backend\ProductVariant\CreatePage;
use App\Tests\Behat\Page\Backend\ProductVariant\IndexPage;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Webmozart\Assert\Assert;

class ManagingProductVariantsContext implements Context
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
     * @param IndexPage $indexPage
     * @param CreatePage $createPage
     */
    public function __construct(IndexPage $indexPage, CreatePage $createPage)
    {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
    }

    /**
     * @When /^I want to browse (this product) variants$/
     */
    public function iWantToBrowseProductVariants(ProductInterface $product)
    {
        $this->indexPage->open(['productId' => $product->getId()]);
    }

    /**
     * @Given /^I want to create a new variant of (this product)$/
     */
    public function iWantToCreateANewProduct(ProductInterface $product)
    {
        $this->createPage->open(['productId' => $product->getId()]);
    }

    /**
     * @When I name it :name
     */
    public function iSpecifyItsNameAs($name = null)
    {
        $this->createPage->nameIt($name);
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
     * @Then /^there should be (\d+) product variants in the list$/
     */
    public function iShouldSeeProductVariantsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then I should (also) see the product variant :name in the list
     */
    public function iShouldSeeTheProductVariantNameInTheList($name)
    {
        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then the :productVariantName variant of the :product product should appear in the website
     */
    public function theProductVariantShouldAppearInTheShop($productVariantName, ProductInterface $product)
    {
        $this->indexPage->open(['productId' => $product->getId()]);

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $productVariantName]));
    }
}
