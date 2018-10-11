<?php

namespace spec\App\Entity;

use App\Entity\Dealer;
use App\Entity\PriceList;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Model\ResourceInterface;

class PriceListSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PriceList::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_has_no_path_by_default()
    {
        $this->getPath()->shouldReturn(null);
    }

    function it_has_no_headers_by_default()
    {
        $this->hasHeaders()->shouldReturn(false);
    }

    function it_has_semicolon_as_delimiter_by_default()
    {
        $this->getDelimiter()->shouldReturn(";");
    }

    function its_delimiter_is_mutable()
    {
        $this->setDelimiter(",");
        $this->getDelimiter()->shouldReturn(",");
    }

    function its_charset_is_utf8_by_default()
    {
        $this->isUtf8()->shouldReturn(true);
    }

    function its_charset_is_mutable()
    {
        $this->setUtf8(false);
        $this->isUtf8()->shouldReturn(false);
    }

    function it_is_not_active_by_default()
    {
        $this->isActive()->shouldReturn(false);
    }

    function it_can_be_active()
    {
        $this->setActive(true);
        $this->isActive()->shouldReturn(true);
    }

    function it_has_no_dealer_by_default()
    {
        $this->getDealer()->shouldReturn(null);
    }

    function its_dealer_is_mutable(Dealer $dealer)
    {
        $this->setDealer($dealer);
        $this->getDealer()->shouldReturn($dealer);
    }
}
