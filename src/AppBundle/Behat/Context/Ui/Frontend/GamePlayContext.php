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
use AppBundle\Entity\GamePlay;
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
     * @Given /^I want to edit (this game play)$/
     */
    public function iWantToEditGamePlay(GamePlay $gamePlay)
    {
        $this->updatePage->open([
            'id' => $gamePlay->getId(),
            'productSlug' => $gamePlay->getProduct()->getSlug(),
        ]);
    }

    /**
     * @When /^I specify its playing date as "([^"]*)"$/
     * @When I do not specify its playing date
     */
    public function iSpecifyItsPlayingDateAs(string $playedAt = null)
    {
        $this->createPage->setPlayedAt($playedAt);
    }

    /**
     * @When /^I specify its duration as "([^"]*)"$/
     * @When I do not specify its duration
     */
    public function iSpecifyItsDurationAs(int $duration = null)
    {
        $this->createPage->setDuration($duration);
    }

    /**
     * @When /^I specify its player count as "([^"]*)"$/
     * @When I do not specify its player count
     */
    public function iSpecifyItsPlayerCountAs(int $playerCount = null)
    {
        $this->createPage->setPlayerCount($playerCount);
    }

    /**
     * @When I change its duration as :playedAt
     */
    public function iChangeItsDurationAtAs($duration)
    {
        $this->updatePage->setDuration($duration);
    }

    /**
     * @When I change its player count as :playerCount
     */
    public function iChangeItsPlayerCountAs($playerCount)
    {
        $this->updatePage->setPlayerCount($playerCount);
    }

    /**
     * @When I add it
     */
    public function iAddIt()
    {
        $this->createPage->submit();
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->submit();
    }
}
