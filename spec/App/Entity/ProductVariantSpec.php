<?php

namespace spec\App\Entity;

use App\Entity\Person;
use App\Entity\ProductVariant;
use App\Entity\ProductVariantImage;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductVariant as BaseProductVariant;

class ProductVariantSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductVariant::class);
    }

    function it_extends_a_product_variant_model(): void
    {
        $this->shouldHaveType(BaseProductVariant::class);
    }

    function it_has_no_released_at_by_default()
    {
        $this->getReleasedAt()->shouldReturn(null);
    }

    function its_released_at_is_mutable(\DateTime $releasedAt)
    {
        $this->setReleasedAt($releasedAt);
        $this->getReleasedAt()->shouldReturn($releasedAt);
    }

    function it_has_no_released_at_precision_by_default()
    {
        $this->getReleasedAtPrecision()->shouldReturn(null);
    }

    function its_released_at_precision_is_mutable()
    {
        $this->setReleasedAtPrecision(ProductVariant::RELEASED_AT_PRECISION_ON_MONTH);
        $this->getReleasedAtPrecision()->shouldReturn(ProductVariant::RELEASED_AT_PRECISION_ON_MONTH);
    }

    function it_initializes_images_collection_by_default()
    {
        $this->getImages()->shouldHaveType(Collection::class);
    }

    function it_adds_image(ProductVariantImage $image)
    {
        $this->addImage($image);
        $this->hasImage($image)->shouldReturn(true);
    }

    function it_removes_image(ProductVariantImage $image)
    {
        $this->addImage($image);
        $this->removeImage($image);
        $this->hasImage($image)->shouldReturn(false);
    }

    function it_initializes_designers_collection_by_default()
    {
        $this->getDesigners()->shouldHaveType(Collection::class);
    }

    function it_adds_designer(Person $designer)
    {
        $this->addDesigner($designer);
        $this->hasDesigner($designer)->shouldReturn(true);
    }

    function it_removes_designer(Person $designer)
    {
        $this->addDesigner($designer);
        $this->removeDesigner($designer);
        $this->hasDesigner($designer)->shouldReturn(false);
    }

    function it_initializes_artists_collection_by_default()
    {
        $this->getArtists()->shouldHaveType(Collection::class);
    }

    function it_adds_artist(Person $artist)
    {
        $this->addArtist($artist);
        $this->hasArtist($artist)->shouldReturn(true);
    }

    function it_removes_artist(Person $artist)
    {
        $this->addArtist($artist);
        $this->removeArtist($artist);
        $this->hasArtist($artist)->shouldReturn(false);
    }

    function it_initializes_publishers_collection_by_default()
    {
        $this->getPublishers()->shouldHaveType(Collection::class);
    }

    function it_adds_publisher(Person $publisher)
    {
        $this->addPublisher($publisher);
        $this->hasPublisher($publisher)->shouldReturn(true);
    }

    function it_removes_publisher(Person $publisher)
    {
        $this->addPublisher($publisher);
        $this->removePublisher($publisher);
        $this->hasPublisher($publisher)->shouldReturn(false);
    }
}
