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

use App\Tests\Behat\Page\Backend\Taxon\CreateForParentPage;
use App\Tests\Behat\Page\Backend\Taxon\CreatePage;
use App\Tests\Behat\Page\Backend\Taxon\IndexByParentPage;
use App\Tests\Behat\Page\Backend\Taxon\IndexPage;
use App\Tests\Behat\Page\Backend\Taxon\UpdatePage;
use Behat\Behat\Context\Context;
use Monofony\Bridge\Behat\Service\SharedStorageInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;

final class ManagingTaxonsContext implements Context
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
     * @var CreatePage
     */
    private $createPage;

    /**
     * @var CreateForParentPage
     */
    private $createForParentPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    public function __construct(
        IndexPage $indexPage,
        IndexByParentPage $indexByParentPage,
        CreatePage $createPage,
        CreateForParentPage $createForParentPage,
        UpdatePage $updatePage,
        SharedStorageInterface $sharedStorage
    ) {
        $this->indexPage = $indexPage;
        $this->indexByParentPage = $indexByParentPage;
        $this->createPage = $createPage;
        $this->createForParentPage = $createForParentPage;
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
     * @Given I want to create a new taxonomy
     */
    public function iWantToCreateANewTaxonomy()
    {
        $this->createPage->open();
    }

    /**
     * @Given /^I want to create a new (theme|mechanism)$/
     */
    public function iWantToCreateANewTaxon(string $taxonCode)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->sharedStorage->get(sprintf('taxonomy_%ss', $taxonCode));
        Assert::notNull($taxon);

        $this->createForParentPage->open(['id' => $taxon->getId()]);
    }

    /**
     * @Given I want to edit :mechanism mechanism
     */
    public function iWantToEditTheMechanism(TaxonInterface $mechanism)
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
     * @When /^I specify (?:their|his|its) code as "([^"]*)"$/
     * @When I do not specify its code
     */
    public function iSpecifyItsCodeAs($code = null)
    {
        $this->createPage->specifyCode($code);
    }

    /**
     * @When /^I specify (?:their|his|its) name as "([^"]*)"$/
     * @When I do not specify its name
     */
    public function iSpecifyItsNameAs($name = null)
    {
        $this->createPage->nameIt($name);
    }

    /**
     * @When /^I specify (?:their|his|its) slug as "([^"]*)"$/
     * @When I do not specify its slug
     */
    public function iSpecifyItsSlugAs($slug = null)
    {
        $this->createPage->specifySlug($slug);
    }

    /**
     * @When I change its name as :name
     */
    public function iChangeItsNameAs(string $name)
    {
        $this->updatePage->nameIt($name);
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

    /**
     * @Then this taxonomy with name :name should appear in the website
     */
    public function thisTaxonomyWithNameShouldAppearInTheWebsite($name)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }
}
