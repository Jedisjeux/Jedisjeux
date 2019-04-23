<?php

namespace spec\App\Entity;

use App\Entity\Person;
use App\Entity\Product;
use App\Entity\ProductFile;
use App\Entity\ProductVideo;
use App\Entity\ProductBarcode;
use App\Entity\ProductVariant;
use App\Entity\ProductVariantImage;
use App\Entity\Taxon;
use App\Entity\YearAward;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\Product as BaseProduct;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class ProductSpec extends ObjectBehavior
{
    function let()
    {
        $this->setCurrentLocale('en_US');
        $this->setFallbackLocale('en_US');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Product::class);
    }

    function it_extends_a_product_model(): void
    {
        $this->shouldHaveType(BaseProduct::class);
    }

    function it_calls_parent_constructor()
    {
        $this->getCreatedAt()->shouldNotReturn(null);
        $this->getAttributes()->shouldHaveType(Collection::class);
        $this->getAssociations()->shouldHaveType(Collection::class);
        $this->getVariants()->shouldHaveType(Collection::class);
        $this->getOptions()->shouldHaveType(Collection::class);
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

    function its_name_is_mutable()
    {
        $this->setName('Puerto Rico');
        $this->getName()->shouldReturn('Puerto Rico');
    }

    function its_updates_first_variant_name(ProductVariant $variant)
    {
        $this->addVariant($variant);

        $variant->setName('Puerto Rico')->shouldBeCalled();

        $this->setName('Puerto Rico');
    }

    function its_short_description_is_mutable()
    {
        $this->setShortDescription('This is an awesome board game.');
        $this->getShortDescription()->shouldReturn('This is an awesome board game.');
    }

    function its_average_rating_is_mutable()
    {
        $this->setAverageRating(7.3);
        $this->getAverageRating()->shouldReturn(7.3);
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

    function its_main_taxon_is_mutable(TaxonInterface $taxon)
    {
        $this->setMainTaxon($taxon);
        $this->getMainTaxon()->shouldReturn($taxon);
    }

    function it_can_filter_taxons_by_code(TaxonInterface $rootTaxon1, TaxonInterface $rootTaxon2, TaxonInterface $taxon1, TaxonInterface $taxon2)
    {
        $rootTaxon1->getCode()->willReturn('xyz');
        $rootTaxon2->getCode()->willReturn('666');
        $this->addTaxon($taxon1);
        $this->addTaxon($taxon2);
        $taxon1->getRoot()->willReturn($rootTaxon1);
        $taxon2->getRoot()->willReturn($rootTaxon2);

        $taxons = $this->getTaxons('xyz');

        $taxons->contains($taxon1)->shouldReturn(true);
        $taxons->contains($taxon2)->shouldReturn(false);
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
        $this->isDurationByPlayer()->shouldReturn(false);
    }

    function its_duration_by_player_is_mutable()
    {
        $this->setDurationByPlayer(true);
        $this->isDurationByPlayer()->shouldReturn(true);
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

    function it_can_get_released_at(ProductVariant $variant, \DateTime $releaseDate)
    {
        $this->addVariant($variant);
        $variant->getReleasedAt()->willReturn($releaseDate);

        $this->getReleasedAt()->shouldReturn($releaseDate);
    }

    function it_has_zero_review_count_by_default()
    {
        $this->getReviewCount()->shouldReturn(0);
    }

    function its_review_count_is_mutable()
    {
        $this->setReviewCount(666);
        $this->getReviewCount()->shouldReturn(666);
    }

    function it_has_zero_commented_review_count_by_default()
    {
        $this->getCommentedReviewCount()->shouldReturn(0);
    }

    function its_commented_review_count_is_mutable()
    {
        $this->setCommentedReviewCount(666);
        $this->getCommentedReviewCount()->shouldReturn(666);
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

    function it_can_get_ratings(ReviewInterface $rating, ReviewInterface $review)
    {
        $review->getComment()->willReturn("This is an awesome product");
        $rating->getComment()->willReturn(null);

        $this->addReview($rating);
        $this->addReview($review);

        $this->getRatings()->shouldContain($rating);
        $this->getRatings()->shouldNotContain($review);
    }

    function it_can_get_commented_reviews(ReviewInterface $rating, ReviewInterface $review)
    {
        $review->getComment()->willReturn("This is an awesome product");
        $rating->getComment()->willReturn(null);

        $this->addReview($rating);
        $this->addReview($review);

        $this->getCommentedReviews()->shouldContain($review);
        $this->getCommentedReviews()->shouldNotContain($rating);
    }

    function it_initializes_article_collection_by_default()
    {
        $this->getArticles()->shouldHaveType(Collection::class);
    }

    function it_initializes_game_play_collection_by_default()
    {
        $this->getGamePlays()->shouldHaveType(Collection::class);
    }

    function it_initializes_video_collection_by_default(): void
    {
        $this->getVideos()->shouldHaveType(Collection::class);
    }

    function it_adds_videos(ProductVideo $video): void
    {
        $video->setProduct($this)->shouldBeCalled();

        $this->addVideo($video);
        $this->hasVideo($video)->shouldReturn(true);
    }

    function it_removes_videos(ProductVideo $video): void
    {
        $this->addVideo($video);

        $video->setProduct(null)->shouldBeCalled();

        $this->removeVideo($video);
        $this->hasVideo($video)->shouldReturn(false);
    }

    function it_initializes_file_collection_by_default(): void
    {
        $this->getFiles()->shouldHaveType(Collection::class);
    }

    function it_adds_files(ProductFile $file): void
    {
        $file->setProduct($this)->shouldBeCalled();

        $this->addFile($file);
        $this->hasFile($file)->shouldReturn(true);
    }

    function it_removes_files(ProductFile $file): void
    {
        $this->addFile($file);

        $file->setProduct(null)->shouldBeCalled();

        $this->removeFile($file);
        $this->hasFile($file)->shouldReturn(false);
    }

    function it_has_no_images_by_default(ProductVariant $variant)
    {
        $this->addVariant($variant);
        $variant->getImages()->willReturn(new ArrayCollection());

        $this->getImages()->shouldHaveCount(0);
    }

    function it_can_get_images(ProductVariant $variant, ProductVariantImage $image)
    {
        $this->addVariant($variant);
        $variant->getImages()->willReturn(new ArrayCollection([$image->getWrappedObject()]));

        $this->getImages()->shouldContain($image);
    }

    function it_has_no_main_image_by_default()
    {
        $this->getMainImage()->shouldReturn(null);
    }

    function it_can_get_main_image(ProductVariant $variant, ProductVariantImage $image)
    {
        $this->addVariant($variant);
        $variant->getMainImage()->willReturn($image);

        $this->getMainImage()->shouldReturn($image);
    }

    function it_has_no_material_image_by_default()
    {
        $this->getMaterialImage()->shouldReturn(null);
    }

    function it_can_get_material_image(ProductVariant $variant, ProductVariantImage $image)
    {
        $this->addVariant($variant);
        $variant->getMaterialImage()->willReturn($image);

        $this->getMaterialImage()->shouldReturn($image);
    }

    function it_can_get_designers(ProductVariant $variant, Person $designer)
    {
        $this->addVariant($variant);
        $variant->getDesigners()->willReturn(new ArrayCollection([$designer->getWrappedObject()]));

        $this->getDesigners()->shouldContain($designer);
    }

    function it_can_get_artists(ProductVariant $variant, Person $artist)
    {
        $this->addVariant($variant);
        $variant->getArtists()->willReturn(new ArrayCollection([$artist->getWrappedObject()]));

        $this->getArtists()->shouldContain($artist);
    }

    function it_can_get_publishers(ProductVariant $variant, Person $publisher)
    {
        $this->addVariant($variant);
        $variant->getPublishers()->willReturn(new ArrayCollection([$publisher->getWrappedObject()]));

        $this->getPublishers()->shouldContain($publisher);
    }

    function it_adds_mechanisms(TaxonInterface $taxon, TaxonInterface $rootTaxon)
    {
        $taxon->getRoot()->willReturn($rootTaxon);
        $rootTaxon->getCode()->willReturn(Taxon::CODE_MECHANISM);

        $this->addMechanism($taxon);

        $this->getMechanisms()->shouldContain($taxon);
    }

    function it_removes_mechanisms(TaxonInterface $taxon, TaxonInterface $rootTaxon)
    {
        $taxon->getRoot()->willReturn($rootTaxon);
        $rootTaxon->getCode()->willReturn(Taxon::CODE_MECHANISM);

        $this->addMechanism($taxon);
        $this->removeMechanism($taxon);

        $this->getMechanisms()->shouldNotContain($taxon);
    }

    function it_adds_themes(TaxonInterface $taxon, TaxonInterface $rootTaxon)
    {
        $taxon->getRoot()->willReturn($rootTaxon);
        $rootTaxon->getCode()->willReturn(Taxon::CODE_THEME);

        $this->addTheme($taxon);

        $this->getThemes()->shouldContain($taxon);
    }

    function it_removes_themes(TaxonInterface $taxon, TaxonInterface $rootTaxon)
    {
        $taxon->getRoot()->willReturn($rootTaxon);
        $rootTaxon->getCode()->willReturn(Taxon::CODE_THEME);

        $this->addTheme($taxon);
        $this->removeTheme($taxon);

        $this->getThemes()->shouldNotContain($taxon);
    }

    function it_initializes_barcode_collection_by_default()
    {
        $this->getBarcodes()->shouldHaveType(Collection::class);
    }

    function it_adds_barcode(ProductBarcode $barcode)
    {
        $barcode->setProduct($this)->shouldBeCalled();

        $this->addBarcode($barcode);
        $this->hasBarcode($barcode)->shouldReturn(true);
    }

    function it_removes_barcode(ProductBarcode $barcode)
    {
        $this->addBarcode($barcode);

        $barcode->setProduct(null)->shouldBeCalled();

        $this->removeBarcode($barcode);
        $this->hasBarcode($barcode)->shouldReturn(false);
    }

    function it_initializes_year_award_collection_by_default()
    {
        $this->getYearAwards()->shouldHaveType(Collection::class);
    }

    function it_adds_year_awards(YearAward $yearAward)
    {
        $yearAward->setProduct($this)->shouldBeCalled();

        $this->addYearAward($yearAward);
        $this->hasYearAward($yearAward)->shouldReturn(true);
    }

    function it_removes_year_awards(YearAward $yearAward)
    {
        $this->addYearAward($yearAward);

        $yearAward->setProduct(null)->shouldBeCalled();

        $this->removeYearAward($yearAward);
        $this->hasYearAward($yearAward)->shouldReturn(false);
    }
}
