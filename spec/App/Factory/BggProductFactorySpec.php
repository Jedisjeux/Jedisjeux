<?php

namespace spec\App\Factory;

use App\Factory\BggProductFactory;
use App\Entity\BggProduct;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;

class BggProductFactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(BggProduct::class, 'https://boardgamegeek.com/xmlapi/boardgame');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BggProductFactory::class);
    }

    function it_can_create_bgg_product_by_path()
    {
        // The BGG XML API now requires an authorization token
        throw new SkippingException('Requires access to the BoardGameGeek API');

        $bggProduct = $this->createByPath('https://boardgamegeek.com/boardgame/3076/puerto-rico');

        $bggProduct->shouldHaveType(BggProduct::class);
        $bggProduct->getName()->shouldReturn('Puerto Rico');
        $bggProduct->getArtists()->shouldNotHaveCount(0);
        $bggProduct->getDesigners()->shouldNotHaveCount(0);
        $bggProduct->getArtists()->shouldNotHaveCount(0);
        $bggProduct->getMechanisms()->shouldNotHaveCount(0);
    }
}
