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

use AppBundle\Behat\Page\Frontend\GamePlay\CreatePage;
use AppBundle\Behat\Page\Frontend\GamePlay\IndexPage;
use AppBundle\Behat\Page\Frontend\GamePlay\UpdatePage;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;

class GamePlayContext implements Context
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
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @param IndexPage $indexPage
     * @param CreatePage $createPage
     * @param UpdatePage $updatePage
     */
    public function __construct(IndexPage $indexPage, CreatePage $createPage, UpdatePage $updatePage)
    {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
    }

    /**
     * @Given /^I want to add game play of (this product)$/
     */
    public function iWantToAddGamePlay(ProductInterface $product)
    {
        $this->createPage->open(['productSlug' => $product->getSlug()]);
    }

    /**
     * @When /^I specify its playing date as "([^"]*)"$/
     * @When I do not specify its playing date
     */
    public function iSpecifyItsPlayingDateAs(string $playedAt = null)
    {
        $this->createPage->specifyPlayingDate($playedAt);
    }

    /**
     * @When /^I specify its duration as "([^"]*)"$/
     * @When I do not specify its duration
     */
    public function iSpecifyItsDurationAs(int $duration = null)
    {
        $this->createPage->specifyDuration($duration);
    }

    /**
     * @When /^I specify its player count as "([^"]*)"$/
     * @When I do not specify its player count
     */
    public function iSpecifyItsPlayerCountAs(int $playerCount = null)
    {
        $this->createPage->specifyPlayerCOunt($playerCount);
    }

    /**
     * @When I add it
     */
    public function iAddIt()
    {
        $this->createPage->submit();
    }
}
