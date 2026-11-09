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

use App\Entity\YearAward;
use App\Tests\Behat\Page\Backend\YearAward\IndexPage;
use App\Tests\Behat\Page\Backend\YearAward\UpdatePage;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Webmozart\Assert\Assert;

final class ManagingYearAwardsContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @param IndexPage $indexPage
     */
    public function __construct(
        IndexPage $indexPage,
        private readonly UpdatePage $updatePage,
        #[Autowire(service: 'app.repository.year_award')]
        private readonly RepositoryInterface $yearAwardRepository,
    ) {
        $this->indexPage = $indexPage;
    }

    /**
     * @When I want to browse year awards
     */
    public function iWantToBrowseYearAwards()
    {
        $this->indexPage->open();
    }

    /**
     * @Then /^there should be (\d+) year awards in the list$/
     */
    public function iShouldSeeGameAwardsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then I should see the year award :name in the list
     */
    public function theGameAwardShould($name)
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage([
            'name' => (string) $name,
        ]));
    }

    /**
     * @Given I want to edit the year award of :year
     */
    public function iWantToEditTheYearAwardOf(string $year): void
    {
        /** @var YearAward $yearAward */
        $yearAward = $this->yearAwardRepository->findOneBy(['year' => $year]);

        $this->updatePage->open(['id' => $yearAward->getId()]);
    }

    /**
     * @When I change its year to :year
     */
    public function iChangeItsYearTo(string $year): void
    {
        $this->updatePage->specifyYear($year);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges(): void
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @Then I should not see the year award :name in the list
     */
    public function iShouldNotSeeTheYearAwardInTheList(string $name): void
    {
        $this->indexPage->open();

        Assert::false($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }
}
