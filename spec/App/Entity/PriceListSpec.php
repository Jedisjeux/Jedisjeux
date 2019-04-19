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

    function it_implements_resource_interface(): void
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_has_no_path_by_default(): void
    {
        $this->getPath()->shouldReturn(null);
    }

    function its_path_is_mutable(): void
    {
        $this->setPath('/path/to/file.csv')->shouldReturn(null);
        $this->getPath()->shouldReturn('/path/to/file.csv');
    }

    function it_has_no_headers_by_default(): void
    {
        $this->hasHeaders()->shouldReturn(false);
    }

    function it_can_have_headers(): void
    {
        $this->setHeaders(false);
        $this->setHeaders(true);

        $this->hasHeaders()->shouldReturn(true);
    }

    function it_has_semicolon_as_delimiter_by_default(): void
    {
        $this->getDelimiter()->shouldReturn(";");
    }

    function its_delimiter_is_mutable(): void
    {
        $this->setDelimiter(",");
        $this->getDelimiter()->shouldReturn(",");
    }

    function its_charset_is_utf8_by_default(): void
    {
        $this->isUtf8()->shouldReturn(true);
    }

    function its_charset_is_mutable(): void
    {
        $this->setUtf8(false);
        $this->isUtf8()->shouldReturn(false);
    }

    function it_is_not_active_by_default(): void
    {
        $this->isActive()->shouldReturn(false);
    }

    function it_can_be_active(): void
    {
        $this->setActive(true);
        $this->isActive()->shouldReturn(true);
    }

    function it_has_no_dealer_by_default(): void
    {
        $this->getDealer()->shouldReturn(null);
    }

    function its_dealer_is_mutable(Dealer $dealer): void
    {
        $this->setDealer($dealer);
        $this->getDealer()->shouldReturn($dealer);
    }
}
