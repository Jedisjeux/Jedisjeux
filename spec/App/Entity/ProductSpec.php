<?php

namespace spec\App\Entity;

use App\Entity\Product;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\Product as BaseProduct;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class ProductSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Product::class);
    }

    function it_extends_a_product_model(): void
    {
        $this->shouldHaveType(BaseProduct::class);
    }

    function it_has_code_by_default()
    {
        $this->getCode()->shouldNotBeNull();
    }

    function its_status_is_new_by_default()
    {
        $this->getStatus()->shouldReturn(Product::STATUS_NEW);
    }

    function its_status_is_mutable()
    {
        $this->setStatus(Product::PENDING_PUBLICATION);
        $this->getStatus()->shouldReturn(Product::PENDING_PUBLICATION);
    }

    function it_initializes_taxon_collection_by_default(): void
    {
        $this->getTaxons()->shouldHaveType(Collection::class);
    }

    function it_adds_taxon(TaxonInterface $taxon)
    {
        $this->addTaxon($taxon);
        $this->hasTaxon($taxon)->shouldReturn(true);
    }

    function it_removes_taxon(TaxonInterface $taxon)
    {
        $this->addTaxon($taxon);
        $this->removeTaxon($taxon);
        $this->hasTaxon($taxon)->shouldReturn(false);
    }

    function it_has_no_min_age_by_default()
    {
        $this->getMinAge()->shouldReturn(null);
    }

    function its_min_age_is_mutable()
    {
        $this->setMinAge(7);
        $this->getMinAge()->shouldReturn(7);
    }

    function it_has_no_min_player_count_by_default()
    {
        $this->getMinPlayerCount()->shouldReturn(null);
    }

    function its_min_player_count_is_mutable()
    {
        $this->setMinPlayerCount(2);
        $this->getMinPlayerCount()->shouldReturn(2);
    }

    function it_has_no_max_player_count_by_default()
    {
        $this->getMaxPlayerCount()->shouldReturn(null);
    }

    function its_max_player_count_is_mutable()
    {
        $this->setMaxPlayerCount(4);
        $this->getMaxPlayerCount()->shouldReturn(4);
    }

    function it_has_no_min_duration_by_default()
    {
        $this->getMinDuration()->shouldReturn(null);
    }

    function its_min_duration_is_mutable()
    {
        $this->setMinDuration(30);
        $this->getMinDuration()->shouldReturn(30);
    }

    function it_has_no_max_duration_by_default()
    {
        $this->getMaxDuration()->shouldReturn(null);
    }

    function its_max_duration_is_mutable()
    {
        $this->setMaxDuration(180);
        $this->getMaxDuration()->shouldReturn(180);
    }

    function its_duration_is_not_by_player_by_default()
    {
        $this->isDurationByPlayer(false);
    }

    function its_duration_by_player_is_mutable()
    {
        $this->setDurationByPlayer(true);
        $this->isDurationByPlayer(true);
    }

    function it_has_no_box_content_by_default()
    {
        $this->getBoxContent()->shouldReturn(null);
    }

    function its_box_content_is_mutable()
    {
        $this->setBoxContent("rulebook, meeples");
        $this->getBoxContent()->shouldReturn("rulebook, meeples");
    }

    function it_has_zero_view_count_by_default()
    {
        $this->getViewCount()->shouldReturn(0);
    }

    function its_view_count_is_mutable()
    {
        $this->setViewCount(666);
        $this->getViewCount()->shouldReturn(666);
    }

    function it_initializes_reviews_collection_by_default()
    {
        $this->getReviews()->shouldHaveType(Collection::class);
    }

    function it_adds_review(ReviewInterface $review)
    {
        $this->addReview($review);
        $this->hasReview($review)->shouldReturn(true);
    }

    function it_removes_review(ReviewInterface $review)
    {
        $this->addReview($review);
        $this->removeReview($review);
        $this->hasReview($review)->shouldReturn(false);
    }

    function it_initializes_article_collection_by_default()
    {
        $this->getArticles()->shouldHaveType(Collection::class);
    }

    function it_initializes_game_play_collection_by_default()
    {
        $this->getGamePlays()->shouldHaveType(Collection::class);
    }
}
