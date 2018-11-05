<?php

namespace spec\App\Entity;

use App\Entity\Person;
use App\Entity\PersonImage;
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
}
