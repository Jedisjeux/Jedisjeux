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

use App\Entity\Topic;
use App\Tests\Behat\Page\Frontend\Post\IndexPage;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

final class RemovingTopicsContext implements Context
{
    public function __construct(
        private readonly IndexPage $topicPage,
    ) {
    }

    /**
     * @When /^I remove (this topic)$/
     */
    public function iRemoveThisTopic(Topic $topic): void
    {
        $this->topicPage->open(['topicId' => $topic->getId()]);
        $this->topicPage->removeTopic();
    }

    /**
     * @Then /^I should not be able to remove (this topic)$/
     */
    public function iShouldNotBeAbleToRemoveThisTopic(Topic $topic): void
    {
        $this->topicPage->open(['topicId' => $topic->getId()]);

        Assert::false($this->topicPage->canRemoveTopic());
    }
}
