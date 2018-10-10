<?php
/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Ui\Frontend;

use App\Behat\Page\Frontend\GamePlay as GamePlayPage;
use App\Behat\Page\Frontend\Post\CreateForGamePlayPage;
use App\Behat\Page\Frontend\Post\UpdateForGamePlayPage;
use App\Entity\GamePlay;
use App\Entity\Post;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class GamePlayPostContext implements Context
{
    /**
     * @var CreateForGamePlayPage
     */
    private $createPage;

    /**
     * @var UpdateForGamePlayPage
     */
    private $updatePage;

    /**
     * @var GamePlayPage\ShowPage
     */
    private $gamePlayShowPage;

    /**
     * @param CreateForGamePlayPage $createPage
     * @param UpdateForGamePlayPage $updatePage
     * @param GamePlayPage\ShowPage $gamePlayShowPage
     */
    public function __construct(
        CreateForGamePlayPage $createPage,
        UpdateForGamePlayPage $updatePage,
        GamePlayPage\ShowPage $gamePlayShowPage
    ) {
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->gamePlayShowPage = $gamePlayShowPage;
    }

    /**
     * @Given /^I want to react on (this game play)$/
     */
    public function iWantToReactOnGamePlay(GamePlay $gamePlay)
    {
        $this->createPage->open(['gamePlayId' => $gamePlay->getId()]);
    }

    /**
     * @Given /^I want to change (this comment)$/
     */
    public function iWantToChangePost(Post $post)
    {
        $this->updatePage->open([
            'id' => $post->getId(),
            'gamePlayId' => $post->getTopic()->getGamePlay()->getId(),
        ]);
    }

    /**
     * @When /^I want to remove (this comment)$/
     */
    public function iWantToRemovePost(Post $post)
    {
        $this->gamePlayShowPage->open([
            'id' => $post->getTopic()->getGamePlay()->getId(),
            'productSlug' => $post->getTopic()->getGamePlay()->getProduct()->getSlug(),
        ]);

        $button = $this->gamePlayShowPage->getRemoveButtonFromPostWithComment($post->getBody());
        Assert::notNull($button, 'Remove button was not found for this comment');

        $button->press();
    }

    /**
     * @When I leave a comment :comment
     * @When I do not leave any comment
     */
    public function iLeaveAComment($comment = null)
    {
        $this->createPage->setComment($comment);
    }

    /**
     * @When I change my comment as :comment
     */
    public function iChangeMyCommentAs($comment)
    {
        $this->updatePage->setComment($comment);
    }

    /**
     * @When I add it
     * @When I try to add it
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
