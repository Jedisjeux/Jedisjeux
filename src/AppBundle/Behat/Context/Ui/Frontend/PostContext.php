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

use AppBundle\Behat\Page\Frontend\Post\CreatePage;
use AppBundle\Behat\Page\Frontend\Post\IndexPage;
use AppBundle\Behat\Page\Frontend\Post\UpdatePage;
use AppBundle\Entity\Topic;
use Behat\Behat\Context\Context;

class PostContext implements Context
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
     * @Given /^I want to reply to (this topic)$/
     */
    public function iWantToReplyToTopic(Topic $topic)
    {
        $this->createPage->open(['topicId' => $topic->getId()]);
    }

    /**
     * @When I leave a comment :comment
     */
    public function iLeaveAComment($comment = null)
    {
        $this->createPage->setComment($comment);
    }

    /**
     * @When I add it
     */
    public function iAddIt()
    {
        $this->createPage->submit();
    }
}
