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
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @param IndexPage $indexPage
     * @param IndexByParentPage $indexByParentPage
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(
        IndexPage $indexPage,
        IndexByParentPage $indexByParentPage,
        SharedStorageInterface $sharedStorage
    ) {
        $this->indexPage = $indexPage;
        $this->indexByParentPage = $indexByParentPage;
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
}
