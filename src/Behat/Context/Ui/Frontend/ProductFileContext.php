<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Behat\Context\Ui\Frontend;

use App\Behat\Page\Frontend\ProductFile\IndexPage;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Webmozart\Assert\Assert;

class ProductFileContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @param IndexPage $indexPage
     */
    public function __construct(IndexPage $indexPage)
    {
        $this->indexPage = $indexPage;
    }

    /**
     * @When /^I check (this product)'s files$/
     */
    public function iCheckThisProductFiles(ProductInterface $product)
    {
        $this->indexPage->open(['slug' => $product->getSlug()]);
    }

    /**
     * @Then /^I should see (\d+) product files in the list$/
     */
    public function iShouldSeeNumberOfProductFilesInTheList($count)
    {
        Assert::same($this->indexPage->countFiles(), (int) $count);
    }

    /**
     * @Then /^I should be notified that there are no files$/
     */
    public function iShouldBeNotifiedThatThereAreNoFiles()
    {
        Assert::true($this->indexPage->hasNoFilesMessage());
    }
}
