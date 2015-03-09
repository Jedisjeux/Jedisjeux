<?php

namespace spec\JDJ\CollectionBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefaultControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\CollectionBundle\Controller\DefaultController');
    }
}
