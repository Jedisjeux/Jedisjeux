<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui\Frontend;

use App\Behat\Page\Frontend\Product\IndexByPersonPage;
use App\Behat\Page\Frontend\Product\IndexByTaxonPage;
use App\Behat\Page\Frontend\Product\IndexPage;
use App\Behat\Page\Frontend\Product\ShowPage;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;
use App\Entity\Person;
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
     * @var IndexByPersonPage
     */
    private $indexByPersonPage;

    /**
     * @param ShowPage          $showPage
     * @param IndexPage         $indexPage
     * @param IndexByTaxonPage  $indexByTaxonPage
     * @param IndexByPersonPage $indexByPersonPage
     */
    public function __construct(
        ShowPage $showPage,
        IndexPage $indexPage,
        IndexByTaxonPage $indexByTaxonPage,
        IndexByPersonPage $indexByPersonPage
    ) {
        $this->showPage = $showPage;
        $this->indexPage = $indexPage;
        $this->indexByTaxonPage = $indexByTaxonPage;
        $this->indexByPersonPage = $indexByPersonPage;
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
     * @When /^I browse products from (person "([^"]+)")$/
     */
    public function iCheckListOfProductsForPerson(Person $person)
    {
        $this->indexByPersonPage->open(['slug' => $person->getSlug()]);
    }

    /**
     * @When /^I check (this product)'s details$/
     * @When I view product :product
     */
    public function iOpenProductPage(ProductInterface $product)
    {
        $this->showPage->open(['slug' => $product->getSlug()]);
    }

    /**
     * @Then I should see the product :productName
     * @Then I should see a product with name :productName
     */
    public function iShouldSeeProduct($productName)
    {
        Assert::true($this->indexPage->isProductOnList($productName));
    }

    /**
     * @Then I should not see the product :productName
     * @Then I should not see a product with name :productName
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
     * @Then I should see :firstItem, :secondItem, :thirdItem as box content
     */
    public function iShouldSeeItemAsBoxContent(...$items)
    {
        foreach ($items as $item) {
            Assert::contains($this->showPage->getBoxContent(), $item);
        }
    }

    /**
     * @Then I should see :numberOfProducts products in the list
     */
    public function iShouldSeeProductsInTheList($numberOfProducts)
    {
        Assert::same($this->indexPage->countProductsItems(), (int) $numberOfProducts);
    }

    /**
     * @Then the first product on the list should have name :name
     */
    public function theFirstProductOnTheListShouldHaveName($name)
    {
        Assert::same($this->indexPage->getFirstProductNameFromList(), $name);
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
     * @Then I should see the theme name :name
     */
    public function iShouldSeeThemeName($name)
    {
        $themes = $this->getProductThemes();

        $found = false;

        foreach ($themes as $theme) {
            if ($name === $theme->getText()) {
                $found = true;
                break;
            }
        }

        Assert::true($found);
    }

    /**
     * @Then I should see the designer name :name
     */
    public function iShouldSeeDesignerName($name)
    {
        $designers = $this->getProductDesigners();

        $found = false;

        foreach ($designers as $designer) {
            if ($name === $designer->getText()) {
                $found = true;
                break;
            }
        }

        Assert::true($found);
    }

    /**
     * @Then I should see the artist name :name
     */
    public function iShouldSeeArtistName($name)
    {
        $artists = $this->getProductArtists();

        $found = false;

        foreach ($artists as $artist) {
            if ($name === $artist->getText()) {
                $found = true;
                break;
            }
        }

        Assert::true($found);
    }

    /**
     * @Then I should see the award name :name
     */
    public function iShouldSeeAwardName($name)
    {
        $awards = $this->getProductAwards();

        $found = false;

        foreach ($awards as $award) {
            if ($name === $award->getText()) {
                $found = true;
                break;
            }
        }

        Assert::true($found);
    }

    /**
     * @Then I should see the publisher name :name
     */
    public function iShouldSeePublisherName($name)
    {
        $artists = $this->getProductPublishers();

        $found = false;

        foreach ($artists as $artist) {
            if ($name === $artist->getText()) {
                $found = true;
                break;
            }
        }

        Assert::true($found);
    }

    /**
     * @Then I should see the variant name :name
     */
    public function iShouldSeeVariantName($name)
    {
        Assert::true($this->showPage->hasVariantNamed($name));
    }

    /**
     * @Then /^I should be able to see (this product)'s details$/
     */
    public function iShouldBeAbleToSeeProductDetails(ProductInterface $product)
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
    public function iShouldNotBeAbleToSeeProductDetails(ProductInterface $product)
    {
        try {
            $this->iOpenProductPage($product);
        } catch (UnexpectedPageException $exception) {
            // nothing else to do
        }

        Assert::false($this->showPage->isOpen(['slug' => $product->getSlug()]));
    }

    /**
     * @When /^I view (oldest|newest) products$/
     */
    public function iViewSortedProducts($sortDirection)
    {
        $sorting = ['createdAt' => 'oldest' === $sortDirection ? 'asc' : 'desc'];

        $this->indexPage->open(['sorting' => $sorting]);
    }

    /**
     * @When /^I view (oldest|newest) products from (taxon "([^"]+)")$/
     */
    public function iViewSortedProductsFromSpecificMechanism($sortDirection, TaxonInterface $taxon)
    {
        $sorting = ['createdAt' => 'oldest' === $sortDirection ? 'asc' : 'desc'];

        $this->indexByTaxonPage->open(['slug' => $taxon->getSlug(), 'sorting' => $sorting]);
    }

    /**
     * @When /^I view (oldest|newest) products from (person "([^"]+)")$/
     */
    public function iViewSortedProductsFromSpecificPerson($sortDirection, Person $person)
    {
        $sorting = ['createdAt' => 'oldest' === $sortDirection ? 'asc' : 'desc'];

        $this->indexByPersonPage->open(['slug' => $person->getSlug(), 'sorting' => $sorting]);
    }

    /**
     * @When /^I view (oldest|newest) release products$/
     */
    public function iViewSortedProductReleases($sortDirection)
    {
        $sorting = ['releasedAt' => 'oldest' === $sortDirection ? 'asc' : 'desc'];

        $this->indexPage->open(['sorting' => $sorting]);
    }

    /**
     * @When /^I view top commented products$/
     */
    public function iViewTopCommentedProducts()
    {
        $sorting = ['commentedReviewCount' => 'desc'];

        $this->indexPage->open(['sorting' => $sorting]);
    }

    /**
     * @When /^I view products with duration up to (\d+) minutes$/
     */
    public function iViewFilteredProductsWithDurationUpToMinutes(int $minutes)
    {
        $criteria = ['minDuration' => ['value' => $minutes]];

        $this->indexPage->open(['criteria' => $criteria]);
    }

    /**
     * @When /^I view products with duration less than (\d+) minutes$/
     */
    public function iViewFilteredProductsWithDurationLessThanMinutes(int $minutes)
    {
        $criteria = ['maxDuration' => ['value' => $minutes]];

        $this->indexPage->open(['criteria' => $criteria]);
    }

    /**
     * @When /^I view products with age up to (\d+) years$/
     */
    public function iViewFilteredProductsWithAgeUpToYears(int $years)
    {
        $criteria = ['minAge' => ['value' => $years]];

        $this->indexPage->open(['criteria' => $criteria]);
    }

    /**
     * @When /^I view products which can be played with (\d+) players$/
     */
    public function iViewFilteredProductsByPlayerCount(int $playerCount)
    {
        $criteria = ['playerCount' => ['value' => $playerCount]];

        $this->indexPage->open(['criteria' => $criteria]);
    }

    /**
     * @Then I should see :count product reviews
     */
    public function iShouldSeeProductReviews($count)
    {
        Assert::same($this->showPage->countReviews(), (int) $count);
    }

    /**
     * @Then I should see :count articles
     */
    public function iShouldSeeArticles($count)
    {
        Assert::same($this->showPage->countArticles(), (int) $count);
    }

    /**
     * @Then I should see :count game plays
     */
    public function iShouldSeeGamePlays($count)
    {
        Assert::same($this->showPage->countGamePlays(), (int) $count);
    }

    /**
     * @Then I should see :count files
     */
    public function iShouldSeeFiles($count)
    {
        Assert::same($this->showPage->countFiles(), (int) $count);
    }

    /**
     * @Then I should see :count product videos
     */
    public function iShouldSeeProductVideos($count)
    {
        Assert::same($this->showPage->countVideos(), (int) $count);
    }

    /**
     * @Then I should see reviews titled :firstReview, :secondReview and :thirdReview
     */
    public function iShouldSeeReviewsTitled(...$reviews)
    {
        foreach ($reviews as $review) {
            Assert::true(
                $this->showPage->hasReviewTitled($review),
                sprintf('Product should have review titled "%s" but it does not.', $review)
            );
        }
    }

    /**
     * @Then I should not see review titled :title
     */
    public function iShouldNotSeeReviewTitled($title)
    {
        Assert::false($this->showPage->hasReviewTitled($title));
    }

    /**
     * @Then I should see articles titled :firstArticle, :secondArticle and :thirdArticle
     */
    public function iShouldSeeArticlesTitled(...$articles)
    {
        foreach ($articles as $article) {
            Assert::true(
                $this->showPage->hasArticleTitled($article),
                sprintf('Article should have article titled "%s" but it does not.', $article)
            );
        }
    }

    /**
     * @Then I should not see article titled :title
     */
    public function iShouldNotSeeArticleTitled($title)
    {
        Assert::false($this->showPage->hasArticleTitled($title));
    }

    /**
     * @Then I should see game plays added by customers :firstCustomer, :secondCustomer and :thirdCustomer
     */
    public function iShouldSeeGamePlaysAddedByCustomer(...$customers)
    {
        foreach ($customers as $customer) {
            Assert::true(
                $this->showPage->hasGamePlayAddedByCustomerEmail($customer),
                sprintf('Game play should have customer with email "%s" but it does not.', $customer)
            );
        }
    }

    /**
     * @Then I should not see game play added by customer :email
     */
    public function iShouldNotSeeGamePlayAddedByCustomerProduct($email)
    {
        Assert::false($this->showPage->hasGamePlayAddedByCustomerEmail($email));
    }

    /**
     * @Then I should see videos titled :firstVideo, :secondVideo and :thirdVideo
     */
    public function iShouldSeeVideosTitled(...$videos)
    {
        foreach ($videos as $video) {
            Assert::true(
                $this->showPage->hasVideoTitled($video),
                sprintf('Product should have video titled "%s" but it does not.', $video)
            );
        }
    }

    /**
     * @Then I should see files titled :firstFile, :secondFile and :thirdFile
     */
    public function iShouldSeeFilesTitled(...$files)
    {
        foreach ($files as $file) {
            Assert::true(
                $this->showPage->hasFileTitled($file),
                sprintf('Product should have file titled "%s" but it does not.', $file)
            );
        }
    }

    /**
     * @Then /^I should(?:| also) see the product association "([^"]+)" with (products "[^"]+" and "[^"]+")$/
     */
    public function iShouldSeeTheProductAssociationWithProducts($productAssociationName, array $products)
    {
        Assert::true(
            $this->showPage->hasAssociation($productAssociationName),
            sprintf('There should be an association named "%s" but it does not.', $productAssociationName)
        );

        foreach ($products as $product) {
            $this->assertProductIsInAssociation($product->getName(), $productAssociationName);
        }
    }

    /**
     * @param string $productName
     * @param string $productAssociationName
     *
     * @throws \InvalidArgumentException
     */
    private function assertProductIsInAssociation($productName, $productAssociationName)
    {
        Assert::true(
            $this->showPage->hasProductInAssociation($productName, $productAssociationName),
            sprintf(
                'There should be an associated product "%s" under association "%s" but it does not.',
                $productName,
                $productAssociationName
            )
        );
    }

    /**
     * @return NodeElement[]
     *
     * @throws \InvalidArgumentException
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    private function getProductMechanisms()
    {
        $mechanisms = $this->showPage->getMechanisms();
        Assert::notNull($mechanisms, 'The product has no mechanisms.');

        return $mechanisms;
    }

    /**
     * @return NodeElement[]
     *
     * @throws \InvalidArgumentException
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    private function getProductThemes()
    {
        $themes = $this->showPage->getThemes();
        Assert::notNull($themes, 'The product has no themes.');

        return $themes;
    }

    /**
     * @return NodeElement[]
     *
     * @throws \InvalidArgumentException
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    private function getProductDesigners()
    {
        $designers = $this->showPage->getDesigners();
        Assert::notNull($designers, 'The product has no designers.');

        return $designers;
    }

    /**
     * @return NodeElement[]
     *
     * @throws \InvalidArgumentException
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    private function getProductArtists()
    {
        $artists = $this->showPage->getArtists();
        Assert::notNull($artists, 'The product has no artists.');

        return $artists;
    }

    /**
     * @return NodeElement[]
     *
     * @throws \InvalidArgumentException
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    private function getProductPublishers()
    {
        $publishers = $this->showPage->getPublishers();
        Assert::notNull($publishers, 'The product has no publishers.');

        return $publishers;
    }

    /**
     * @return NodeElement[]
     *
     * @throws \InvalidArgumentException
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    private function getProductAwards()
    {
        $awards = $this->showPage->getAwards();
        Assert::notNull($awards, 'The product has no awards.');

        return $awards;
    }
}
