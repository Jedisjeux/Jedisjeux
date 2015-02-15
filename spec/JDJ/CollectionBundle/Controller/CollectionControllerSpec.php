<?php

namespace spec\JDJ\CollectionBundle\Controller;

use JDJ\CollectionBundle\Repository\CollectionRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;


class CollectionControllerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\CollectionBundle\Controller\CollectionController');
    }

}
