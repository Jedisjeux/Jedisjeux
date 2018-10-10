<?php

namespace spec\App\Entity;

use App\Entity\Dealer;
use App\Entity\DealerContact;
use App\Entity\DealerImage;
use App\Entity\PriceList;
use App\Entity\PubBanner;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Model\ResourceInterface;

class DealerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Dealer::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function its_code_is_mutable()
    {
        $this->setCode("philibert");
        $this->getCode()->shouldReturn("philibert");
    }

    function it_has_no_name_by_default()
    {
        $this->getName()->shouldReturn(null);
    }

    function its_name_is_mutable()
    {
        $this->setName("Philibert");
        $this->getName()->shouldReturn("Philibert");
    }

    function it_has_no_image_by_default()
    {
        $this->getImage()->shouldReturn(null);
    }

    function its_image_is_mutable(DealerImage $image)
    {
        $this->setImage($image);
        $this->getImage()->shouldReturn($image);
    }

    function it_has_no_price_list_by_default()
    {
        $this->getPriceList()->shouldReturn(null);
    }

    function its_price_list_is_mutable(PriceList $priceList)
    {
        $this->setPriceList($priceList);
        $this->getPriceList()->shouldReturn($priceList);
    }

    function it_initializes_pub_banners_collection_by_default(): void
    {
        $this->getPubBanners()->shouldHaveType(Collection::class);
    }

    function it_adds_pub_banner(PubBanner $pubBanner)
    {
        $this->addPubBanner($pubBanner);
        $this->hasPubBanner($pubBanner)->shouldReturn(true);
    }

    function it_removes_pub_banner(PubBanner $pubBanner)
    {
        $this->addPubBanner($pubBanner);

        $pubBanner->setDealer(null)->shouldBeCalled();

        $this->removePubBanner($pubBanner);
        $this->hasPubBanner($pubBanner)->shouldReturn(false);
    }

    function it_initializes_contacts_collection_by_default(): void
    {
        $this->getContacts()->shouldHaveType(Collection::class);
    }

    function it_adds_contact(DealerContact $contact)
    {
        $this->addContact($contact);
        $this->hasContact($contact)->shouldReturn(true);
    }

    function it_removes_contact(DealerContact $contact)
    {
        $this->addContact($contact);

        $contact->setDealer(null)->shouldBeCalled();

        $this->removeContact($contact);
        $this->hasContact($contact)->shouldReturn(false);
    }
}
