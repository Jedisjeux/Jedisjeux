<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Ui\Backend;

use AppBundle\Behat\Page\Backend\ProductReview\IndexPage;
use AppBundle\Behat\Page\Backend\ProductReview\UpdatePage;
use AppBundle\Behat\Service\Resolver\CurrentPageResolverInterface;
use AppBundle\Entity\ProductReview;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingProductReviewsContext implements Context
{
    /**
     * @var IndexPage
     */
    private $indexPage;

    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * @var CurrentPageResolverInterface
     */
    private $currentPageResolver;

    /**
     * ManagingPeopleContext constructor.
     *
     * @param IndexPage $indexPage
     * @param UpdatePage $updatePage
     * @param CurrentPageResolverInterface $currentPageResolver
     */
    public function __construct(
        IndexPage $indexPage,
        UpdatePage $updatePage,
        CurrentPageResolverInterface $currentPageResolver)
    {
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @When I want to browse product reviews
     */
    public function iWantToBrowseProductReviews()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I want to edit :productReview product review
     */
    public function iWantToEditTheProductReview(ProductReview $productReview)
    {
        $this->updatePage->open(['id' => $productReview->getId()]);
    }

    /**
     * @When I change its title as :title
     */
    public function iChangeItsTitleAs($title)
    {
        $this->updatePage->changeTitle($title);
    }

    /**
     * @When I change its comment as :comment
     */
    public function iChangeItsCommentAs($comment)
    {
        $this->updatePage->changeComment($comment);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I delete the :title product review
     */
    public function iDeleteProductReviewWithTitle($title)
    {
        $this->iWantToBrowseProductReviews();
        $this->indexPage->deleteResourceOnPage(['title' => $title]);
    }

    /**
     * @Then /^there should be (\d+) product reviews in the list$/
     */
    public function iShouldSeeProductReviewsInTheList($number)
    {
        Assert::same($this->indexPage->countItems(), (int) $number);
    }

    /**
     * @Then I should (also) see the product review :title in the list
     */
    public function iShouldSeeTheProductReviewTitleInTheList($title)
    {
        Assert::true($this->indexPage->isSingleResourceOnPage(['title' => $title]));
    }

    /**
     * @Then this product review with title :title should appear in the website
     */
    public function thisProductReviewWithTitleShouldAppearInTheWebsite($title)
    {
        $this->iWantToBrowseProductReviews();
        Assert::true($this->indexPage->isSingleResourceOnPage(['title' => $title]));
    }

    /**
     * @Then this product review body should be :body
     */
    public function thisProductReviewBodyShouldBe($body)
    {
        $this->assertElementValue('body', $body);
    }

    /**
     * @Then there should not be :title product review anymore
     */
    public function thereShouldBeNoProductReviewAnymore($title)
    {
        $this->iWantToBrowseProductReviews();
        Assert::false($this->indexPage->isSingleResourceOnPage(['title' => $title]));
    }

    /**
     * @param string $element
     * @param string $value
     */
    private function assertElementValue($element, $value)
    {
        Assert::true(
            $this->updatePage->hasResourceValues([$element => $value]),
            sprintf('ProductReview should have %s with %s value.', $element, $value)
        );
    }
}
