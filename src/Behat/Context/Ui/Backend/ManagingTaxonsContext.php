<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui\Backend;

use App\Behat\Page\Backend\Taxon\IndexByParentPage;
use App\Behat\Page\Backend\Taxon\IndexPage;
use App\Behat\Page\Backend\Taxon\UpdatePage;
use App\Behat\Service\SharedStorageInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;

class ManagingTaxonsContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var IndexByParentPage
     */
    private $indexByParentPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @param IndexPage $indexPage
     * @param IndexByParentPage $indexByParentPage
     * @param UpdatePage $updatePage
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(
        IndexPage $indexPage,
        IndexByParentPage $indexByParentPage,
        UpdatePage $updatePage,
        SharedStorageInterface $sharedStorage
    ) {
        $this->indexPage = $indexPage;
        $this->indexByParentPage = $indexByParentPage;
        $this->updatePage = $updatePage;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given I want to browse taxons
     */
    public function iWantToBrowseTaxons()
    {
        $this->indexPage->open();
    }

    /**
     * @Given /^I want to browse (mechanisms|themes)$/
     */
    public function iWantToBrowseSpecificTaxons(string $taxonCode)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->sharedStorage->get(sprintf('taxonomy_%s', $taxonCode));
        Assert::notNull($taxon);

        $this->indexByParentPage->open(['id' => $taxon->getId()]);
    }

    /**
     * @Given I want to edit :mechanism mechanism
     */
    public function iWantToEditTheTaxon(TaxonInterface $mechanism)
    {
        $this->updatePage->open(['id' => $mechanism->getId()]);
    }

    /**
     * @When /^I delete (mechanism|theme) with name "([^"]+)"$/
     */
    public function iDeleteTaxonWithName($taxonCode, $name)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->sharedStorage->get(sprintf('taxonomy_%ss', $taxonCode));
        Assert::notNull($taxon);

        $this->indexPage->deleteResourceOnPage(['name' => $name]);
    }

    /**
     * @When I change its name as :name
     */
    public function iChangeItsNameAs(string $name)
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
     * @Then /^I should see (\d+) taxons on the list$/
     */
    public function iShouldSeeTaxonsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then I should (also )see the taxon named :name in the list
     */
    public function iShouldSeeTheTaxonNamedInTheList($name)
    {
        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then /^this (mechanism|theme) with name "([^"]+)" should appear in the website$/
     */
    public function thisTaxonWithNameShouldAppearInTheWebsite($taxonCode, $name)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->sharedStorage->get(sprintf('taxonomy_%ss', $taxonCode));
        Assert::notNull($taxon);

        $this->indexByParentPage->open(['id' => $taxon->getId()]);

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then /^there should not be "([^"]+)" (mechanism|theme) anymore$/
     */
    public function thereShouldBeNoTaxonAnymore($name, $taxonCode)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->sharedStorage->get(sprintf('taxonomy_%ss', $taxonCode));
        Assert::notNull($taxon);

        $this->indexByParentPage->open(['id' => $taxon->getId()]);

        Assert::false($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }
}
