<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\Product;
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
        $this->getAgeMin()->shouldReturn(null);
    }

    function its_min_age_is_mutable()
    {
        $this->setAgeMin(7);
        $this->getAgeMin()->shouldReturn(7);
    }

    function it_has_no_min_player_count_by_default()
    {
        $this->getJoueurMin()->shouldReturn(null);
    }

    function its_min_player_count_is_mutable()
    {
        $this->setJoueurMin(2);
        $this->getJoueurMin()->shouldReturn(2);
    }

    function it_has_no_max_player_count_by_default()
    {
        $this->getJoueurMax()->shouldReturn(null);
    }

    function its_max_player_count_is_mutable()
    {
        $this->setJoueurMax(4);
        $this->getJoueurMax()->shouldReturn(4);
    }

    function it_has_no_min_duration_by_default()
    {
        $this->getDurationMin()->shouldReturn(null);
    }

    function its_min_duration_is_mutable()
    {
        $this->setDurationMin(30);
        $this->getDurationMin()->shouldReturn(30);
    }

    function it_has_no_max_duration_by_default()
    {
        $this->getDurationMax()->shouldReturn(null);
    }

    function its_max_duration_is_mutable()
    {
        $this->setDurationMax(180);
        $this->getDurationMax()->shouldReturn(180);
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

    function it_has_no_materiel_by_default()
    {
        $this->getMateriel()->shouldReturn(null);
    }

    function its_materiel_is_mutable()
    {
        $this->setMateriel("rulebook, meeples");
        $this->getMateriel()->shouldReturn("rulebook, meeples");
    }

    function it_has_no_goal_by_default()
    {
        $this->getBut()->shouldReturn(null);
    }

    function its_goal_is_mutable()
    {
        $this->setBut("You have to conquer the world");
        $this->getBut()->shouldReturn("You have to conquer the world");
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
