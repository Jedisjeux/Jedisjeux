<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Ui\Frontend;

use AppBundle\Behat\Page\Frontend\Product\IndexByTaxonPage;
use AppBundle\Behat\Page\Frontend\Product\IndexPage;
use AppBundle\Behat\Page\Frontend\Product\ShowPage;
use AppBundle\Behat\Page\UnexpectedPageException;
use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductContext implements Context
{
    /**
     * @var ShowPage
     */
    private $showPage;

    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var IndexByTaxonPage
     */
    private $indexByTaxonPage;

    /**
     * @param ShowPage $showPage
     * @param IndexPage $indexPage
     * @param IndexByTaxonPage $indexByTaxonPage
     */
    public function __construct(ShowPage $showPage, IndexPage $indexPage, IndexByTaxonPage $indexByTaxonPage)
    {
        $this->showPage = $showPage;
        $this->indexPage = $indexPage;
        $this->indexByTaxonPage = $indexByTaxonPage;
    }

    /**
     * @When I want to browse products
     */
    public function iWantToBrowseProducts()
    {
        $this->indexPage->open();
    }

    /**
     * @When /^I browse products from (taxon "([^"]+)")$/
     */
    public function iCheckListOfProductsForTaxon(TaxonInterface $taxon)
    {
        $this->indexByTaxonPage->open(['slug' => $taxon->getSlug()]);
    }

    /**
     * @When /^I check (this product)'s details$/
     */
    public function iOpenProductPage(ProductInterface $product)
    {
        $this->showPage->open(['slug' => $product->getSlug()]);
    }

    /**
     * @Then I should see the product :productName
     */
    public function iShouldSeeProduct($productName)
    {
        Assert::true($this->indexPage->isProductOnList($productName));
    }

    /**
     * @Then I should not see the product :productName
     */
    public function iShouldNotSeeProduct($productName)
    {
        Assert::false($this->indexPage->isProductOnList($productName));
    }

    /**
     * @Then I should see the product name :name
     */
    public function iShouldSeeProductName($name)
    {
        Assert::same($this->showPage->getName(), $name);
    }

    /**
     * @Then I should see the mechanism name :name
     */
    public function iShouldSeeMechanismName($name)
    {
        $mechanisms = $this->getProductMechanisms();

        $found = false;

        foreach ($mechanisms as $mechanism) {
            if ($name === $mechanism->getText()) {
                $found = true;
                break;
            }
        }

        Assert::true($found);
    }

    /**
     * @Then /^I should be able to see (this product)'s details$/
     */
    public function iShouldBeAbleToSeeDashboard(ProductInterface $product)
    {
        try {
            $this->iOpenProductPage($product);

        } catch (UnexpectedPageException $exception) {
            // nothing else to do
        }

        Assert::true($this->showPage->isOpen(['slug' => $product->getSlug()]));
    }

    /**
     * @Then /^I should not be able to see (this product)'s details$/
     */
    public function iShouldNotBeAbleToSeeDashboard(ProductInterface $product)
    {
        try {
            $this->iOpenProductPage($product);

        } catch (UnexpectedPageException $exception) {
            // nothing else to do
        }

        Assert::false($this->showPage->isOpen(['slug' => $product->getSlug()]));
    }

    /**
     * @return NodeElement[]
     *
     * @throws \InvalidArgumentException
     */
    private function getProductMechanisms()
    {
        $mechanisms = $this->showPage->getMechanisms();
        Assert::notNull($mechanisms, 'The product has no mechanisms.');

        return $mechanisms;
    }
}
