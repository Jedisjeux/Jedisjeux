<?php

namespace spec\JDJ\JediZoneBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StatusControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\JediZoneBundle\Controller\StatusController');
    }
}
