<?php

namespace AppBundle\Behat\Context\Ui\Frontend;

use AppBundle\Behat\Page\Frontend\ProductReview\UpdatePage;
use AppBundle\Entity\ProductReview;
use Behat\Behat\Context\Context;

class ProductReviewContext implements Context
{
    /**
     * @var UpdatePage
     */
    private $updatePage;

    /**
     * ProductReviewContext constructor.
     * @param UpdatePage $updatePage
     */
    public function __construct(UpdatePage $updatePage)
    {
        $this->updatePage = $updatePage;
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
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->submit();
    }
}
