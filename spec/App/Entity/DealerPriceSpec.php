<?php

namespace spec\App\Entity;

use App\Entity\Dealer;
use App\Entity\DealerPrice;
use App\Entity\Product;
use PhpSpec\ObjectBehavior;

class DealerPriceSpec extends ObjectBehavior
{
    function it_has_no_dealer_by_default(): void
    {
        $this->getDealer()->shouldReturn(null);
    }

    function its_dealer_is_mutable(Dealer $dealer): void
    {
        $this->setDealer($dealer);
        $this->getDealer()->shouldReturn($dealer);
    }

    function it_has_no_product_by_default(): void
    {
        $this->getProduct()->shouldReturn(null);
    }

    function its_product_is_mutable(Product $product): void
    {
        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
    }

    function it_has_no_url_by_default(): void
    {
        $this->getUrl()->shouldReturn(null);
    }

    function its_url_is_mutable(): void
    {
        $this->setUrl('http://example.com');
        $this->getUrl()->shouldReturn('http://example.com');
    }

    function it_has_no_name_by_default(): void
    {
        $this->getName()->shouldReturn(null);
    }

    function its_name_is_mutable(): void
    {
        $this->setName('Puerto Rico');
        $this->getName()->shouldReturn('Puerto Rico');
    }

    function it_has_no_price_by_default(): void
    {
        $this->getPrice()->shouldReturn(null);
    }

    function its_price_is_mutable(): void
    {
        $this->setPrice(2200);
        $this->getPrice()->shouldReturn(2200);
    }

    function it_has_no_barcode_by_default(): void
    {
        $this->getBarcode()->shouldReturn(null);
    }

    function its_barcode_is_mutable(): void
    {
        $this->setBarcode('0123456789');
        $this->getBarcode()->shouldReturn('0123456789');
    }

    function it_has_no_status_by_default(): void
    {
        $this->getStatus()->shouldReturn(null);
    }

    function its_status_is_mutable(): void
    {
        $this->setStatus(DealerPrice::STATUS_AVAILABLE);
        $this->getStatus()->shouldReturn(DealerPrice::STATUS_AVAILABLE);
    }
}
