<?php

namespace AppBundle\Behat\Context\Ui\Frontend;

use AppBundle\Behat\Page\Frontend\ProductReview\CreatePage;
use AppBundle\Behat\Page\Frontend\ProductReview\IndexPage;
use AppBundle\Behat\Page\Frontend\ProductReview\UpdatePage;
use AppBundle\Entity\ProductReview;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Webmozart\Assert\Assert;

class ProductReviewContext implements Context
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
     * @When /^I check (this product)'s reviews$/
     */
    public function iCheckThisProductReviews(ProductInterface $product)
    {
        $this->indexPage->open(['slug' => $product->getSlug()]);
    }

    /**
     * @Given /^I want to review (this product)$/
     */
    public function iWantToReviewAProduct(ProductInterface $product)
    {
        $this->createPage->open([
            'productId' => $product->getId(),
        ]);
    }

    /**
     * @Given /^I want to edit (this product review)$/
     */
    public function iWantToEditProductReview(ProductReview $productReview)
    {
        $this->updatePage->open([
            'id' => $productReview->getId(),
            'productId' => $productReview->getReviewSubject()->getId(),
        ]);
    }

    /**
     * @When I change its title as :title
     */
    public function iChangeItsTitleAtAs($title)
    {
        $this->updatePage->setTitle($title);
    }

    /**
     * @When I change its comment as :comment
     */
    public function iChangeItsCommentAtAs($comment)
    {
        $this->updatePage->setComment($comment);
    }

    /**
     * @When I leave a comment :comment, titled :title
     */
    public function iLeaveACommentTitled($comment = null, $title = null)
    {
        $this->createPage->setTitle($title);
        $this->createPage->setComment($comment);
    }

    /**
     * @When I rate it with :rate point(s)
     */
    public function iRateItWithPoints($rate)
    {
        $this->createPage->rateReview($rate);
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

    /**
     * @Then /^I should see (\d+) product reviews in the list$/
     */
    public function iShouldSeeNumberOfProductReviewsInTheList($count)
    {
        Assert::same($this->indexPage->countReviews(), (int) $count);
    }

    /**
     * @Then /^I should be notified that there are no reviews$/
     */
    public function iShouldBeNotifiedThatThereAreNoReviews()
    {
        Assert::true($this->indexPage->hasNoReviewsMessage());
    }
}
