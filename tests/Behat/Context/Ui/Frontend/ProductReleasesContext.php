<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Context\Ui\Frontend;

use App\Tests\Behat\Page\Frontend\Product\ReleasesPage;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

final class ProductReleasesContext implements Context
{
    private const PERIODS = [
        'releases of the month' => 'this-month',
        'upcoming releases' => 'next-months',
        'past releases' => 'previous-months',
    ];

    public function __construct(
        private readonly ReleasesPage $releasesPage,
    ) {
    }

    /**
     * @When I check the releases
     */
    public function iCheckTheReleases(): void
    {
        $this->releasesPage->open();
    }

    /**
     * @Then /^I should see the product "([^"]+)" in the (releases of the month|upcoming releases|past releases)$/
     */
    public function iShouldSeeTheProductInTheReleases(string $name, string $period): void
    {
        Assert::oneOf($name, $this->releasesPage->getProductNames(self::PERIODS[$period]));
    }

    /**
     * @Then /^I should not see the product "([^"]+)" in the (releases of the month|upcoming releases|past releases)$/
     */
    public function iShouldNotSeeTheProductInTheReleases(string $name, string $period): void
    {
        Assert::notContains(implode('|', $this->releasesPage->getProductNames(self::PERIODS[$period])), $name);
    }
}
