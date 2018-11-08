<?php

namespace spec\App\Entity;

use App\Entity\Person;
use App\Entity\PersonImage;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\Taxon;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class PersonSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Person::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_has_code_by_default()
    {
        $this->getCode()->shouldNotBeNull();
    }

    function its_code_is_mutable()
    {
        $this->setCode('XYZ');
        $this->getCode()->shouldReturn('XYZ');
    }

    function its_slug_is_mutable()
    {
        $this->setSlug('darth-vader');
        $this->getSlug()->shouldReturn('darth-vader');
    }

    function it_has_no_last_name_by_default()
    {
        $this->getLastName()->shouldReturn(null);
    }

    function its_last_name_is_mutable()
    {
        $this->setLastName("Vader");
        $this->getLastName()->shouldReturn("Vader");
    }

    function it_has_no_first_name_by_default()
    {
        $this->getFirstName()->shouldReturn(null);
    }

    function its_first_name_is_mutable()
    {
        $this->setFirstName("Darth");
        $this->getFirstName()->shouldReturn("Darth");
    }

    function it_can_get_full_name(): void
    {
        $this->setFirstName('Edward');
        $this->setLastName('Kenway');

        $this->getFullName()->shouldReturn('Edward Kenway');

        $this->setFirstName(null);
        $this->getFullName()->shouldReturn('Kenway');
    }

    function it_has_no_website_by_default()
    {
        $this->getWebsite()->shouldReturn(null);
    }

    function its_website_is_mutable()
    {
        $this->setWebsite("http://example.com");
        $this->getWebsite()->shouldReturn("http://example.com");
    }

    function it_has_no_description_by_default()
    {
        $this->getDescription()->shouldReturn(null);
    }

    function its_description_is_mutable()
    {
        $this->setDescription("I am your father");
        $this->getDescription()->shouldReturn("I am your father");
    }

    function it_initializes_product_count_to_zero_by_default()
    {
        $this->getProductCount()->shouldReturn(0);
    }

    function its_product_count_is_mutable()
    {
        $this->setProductCount(666);
        $this->getProductCount()->shouldReturn(666);
    }

    function its_product_count_as_artist_is_mutable()
    {
        $this->setProductCountAsArtist(666);
        $this->getProductCountAsArtist()->shouldReturn(666);
    }

    function its_product_count_as_designer_is_mutable()
    {
        $this->setProductCountAsDesigner(666);
        $this->getProductCountAsDesigner()->shouldReturn(666);
    }

    function its_product_count_as_publisher_is_mutable()
    {
        $this->setProductCountAsPublisher(666);
        $this->getProductCountAsPublisher()->shouldReturn(666);
    }

    function it_initializes_images_collection_by_default()
    {
        $this->getImages()->shouldHaveType(Collection::class);
    }

    function it_adds_image(PersonImage $image)
    {
        $image->setPerson($this)->shouldBeCalled();

        $this->addImage($image);
        $this->hasImage($image)->shouldReturn(true);
    }

    function it_removes_image(PersonImage $image)
    {
        $this->addImage($image);
        $this->removeImage($image);
        $this->hasImage($image)->shouldReturn(false);
    }

    function it_has_no_first_image_by_default()
    {
        $this->getFirstImage()->shouldReturn(null);
    }

    function it_can_get_first_image(
        PersonImage $fisrtImage,
        PersonImage $secondImage
    ): void {
        $this->addImage($fisrtImage);
        $this->addImage($secondImage);

        $this->getFirstImage()->shouldReturn($fisrtImage);
    }

    function it_initializes_taxon_collection_by_default()
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

    function its_zone_is_mutable(TaxonInterface $previousZone, TaxonInterface $newZone, TaxonInterface $rootZone)
    {
        $previousZone->getRoot()->willReturn($rootZone);
        $newZone->getRoot()->willReturn($rootZone);
        $rootZone->getCode()->willReturn(Taxon::CODE_ZONE);

        $this->setZone($previousZone);
        $this->getZone()->shouldReturn($previousZone);

        $this->setZone($newZone);
        $this->getZone()->shouldReturn($newZone);
    }
}
