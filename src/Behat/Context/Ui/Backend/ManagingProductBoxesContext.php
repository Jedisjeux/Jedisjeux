<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui\Backend;

use App\Behat\Page\Backend\ProductBox\CreatePage;
use App\Behat\Page\Backend\ProductBox\IndexPage;
use App\Behat\Page\Backend\ProductBox\UpdatePage;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Webmozart\Assert\Assert;

class ManagingProductBoxesContext implements Context
{
    /**
     * @var CreatePage
     */
    private $createPage;

    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @param CreatePage $createPage
     * @param IndexPage  $indexPage
     * @param UpdatePage $updatePage
     */
    public function __construct(CreatePage $createPage, IndexPage $indexPage, UpdatePage $updatePage)
    {
        $this->createPage = $createPage;
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
    }

    /**
     * @When /^I want to create a new product box$/
     */
    public function iWantToAddProductBox(): void
    {
        $this->createPage->open();
    }

    /**
     * @When I attach the :path image
     */
    public function iAttachImage($path)
    {
        $this->createPage->attachImage($path);
    }

    /**
     * @When I specify its product as :product
     * @When I do not specify its product
     */
    public function iSpecifyItsProductAs(?ProductInterface $product)
    {
        $this->createPage->specifyProduct($product);
    }

    /**
     * @When /^I specify its height as (\d+)$/
     * @When I do not specify its height
     */
    public function iSpecifyItsHeightAs(?int $height = null)
    {
        $this->createPage->specifyHeight($height);
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
     * @Then the box for product :product should appear in the website
     * @Then I should see the box for product :product in the list
     */
    public function theProductBoxShould(ProductInterface $product)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['product' => $product->getName()]));
    }
}
