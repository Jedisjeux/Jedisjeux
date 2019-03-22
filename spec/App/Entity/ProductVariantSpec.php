<?php

namespace spec\App\Entity;

use App\Entity\Person;
use App\Entity\ProductBox;
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

    function it_initializes_creation_date_by_default(): void
    {
        $this->getCreatedAt()->shouldHaveType(\DateTimeInterface::class);
    }

    function its_creation_date_is_mutable(\DateTime $date): void
    {
        $this->setCreatedAt($date);
        $this->getCreatedAt()->shouldReturn($date);
    }

    function it_has_no_released_at_by_default(): void
    {
        $this->getReleasedAt()->shouldReturn(null);
    }

    function its_released_at_is_mutable(\DateTime $releasedAt): void
    {
        $this->setReleasedAt($releasedAt);
        $this->getReleasedAt()->shouldReturn($releasedAt);
    }

    function it_has_no_released_at_precision_by_default()
    {
        $this->getReleasedAtPrecision()->shouldReturn(null);
    }

    function its_released_at_precision_is_mutable(): void
    {
        $this->setReleasedAtPrecision(ProductVariant::RELEASED_AT_PRECISION_ON_MONTH);
        $this->getReleasedAtPrecision()->shouldReturn(ProductVariant::RELEASED_AT_PRECISION_ON_MONTH);
    }

    function it_initializes_images_collection_by_default()
    {
        $this->getImages()->shouldHaveType(Collection::class);
    }

    function it_adds_image(ProductVariantImage $image): void
    {
        $image->setVariant($this)->shouldBeCalled();

        $this->addImage($image);
        $this->hasImage($image)->shouldReturn(true);
    }

    function it_removes_image(ProductVariantImage $image): void
    {
        $this->addImage($image);
        $this->removeImage($image);
        $this->hasImage($image)->shouldReturn(false);
    }

    function it_initializes_designers_collection_by_default(): void
    {
        $this->getDesigners()->shouldHaveType(Collection::class);
    }

    function it_adds_designer(Person $designer): void
    {
        $this->addDesigner($designer);
        $this->hasDesigner($designer)->shouldReturn(true);
    }

    function it_removes_designer(Person $designer): void
    {
        $this->addDesigner($designer);
        $this->removeDesigner($designer);
        $this->hasDesigner($designer)->shouldReturn(false);
    }

    function it_initializes_artists_collection_by_default(): void
    {
        $this->getArtists()->shouldHaveType(Collection::class);
    }

    function it_adds_artist(Person $artist): void
    {
        $this->addArtist($artist);
        $this->hasArtist($artist)->shouldReturn(true);
    }

    function it_removes_artist(Person $artist): void
    {
        $this->addArtist($artist);
        $this->removeArtist($artist);
        $this->hasArtist($artist)->shouldReturn(false);
    }

    function it_initializes_publishers_collection_by_default(): void
    {
        $this->getPublishers()->shouldHaveType(Collection::class);
    }

    function it_adds_publisher(Person $publisher): void
    {
        $this->addPublisher($publisher);
        $this->hasPublisher($publisher)->shouldReturn(true);
    }

    function it_removes_publisher(Person $publisher): void
    {
        $this->addPublisher($publisher);
        $this->removePublisher($publisher);
        $this->hasPublisher($publisher)->shouldReturn(false);
    }

    function it_initializes_boxes_collection_by_default(): void
    {
        $this->getBoxes()->shouldHaveType(Collection::class);
    }

    function it_adds_boxes(ProductBox $box): void
    {
        $this->addBox($box);
        $this->hasBox($box)->shouldReturn(true);
    }

    function it_removes_boxes(ProductBox $box): void
    {
        $this->addBox($box);
        $this->removeBox($box);
        $this->hasBox($box)->shouldReturn(false);
    }

    function it_has_no_enabled_box_by_default(): void
    {
        $this->getEnabledBox()->shouldReturn(null);
    }

    function it_can_get_enabled_box(ProductBox $disabledBox, ProductBox $enabledBox): void
    {
        $disabledBox->isEnabled()->willReturn(false);
        $enabledBox->isEnabled()->willReturn(true);

        $this->addBox($disabledBox);
        $this->addBox($enabledBox);

        $this->getEnabledBox()->shouldReturn($enabledBox);
    }
}
