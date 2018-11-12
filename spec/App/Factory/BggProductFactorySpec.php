<?php

namespace spec\App\Factory;

use App\Factory\BggProductFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BggProductFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BggProductFactory::class);
    }
}
