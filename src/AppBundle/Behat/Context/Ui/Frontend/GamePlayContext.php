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

use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;

class GamePlayContext implements Context
{
    /**
     * @Given /^I want to add game play of (this product)$/
     */
    public function iWantToAddGamePlay(ProductInterface $product)
    {
        // $this->createPage->open();
    }

    /**
     * @When /^I specify its playing date as "([^"]*)"$/
     * @When I do not specify its playing date
     */
    public function iSpecifyItsPlayingDateAs(string $playedAt = null)
    {
        // $this->createPage->specifyPlayingDate($playedAt);
    }

    /**
     * @When I add it
     */
    public function iAddIt()
    {
        // $this->createPage->submit();
    }
}
