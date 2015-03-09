<?php

namespace spec\JDJ\CollectionBundle\Service;

use Doctrine\ORM\EntityManager;
use JDJ\CollectionBundle\Entity\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CollectionServiceSpec extends ObjectBehavior
{
    function let(EntityManager $em)
    {
        $this->beConstructedWith($em, "JDJCollectionBundle:Collection");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\CollectionBundle\Service\CollectionService');
    }

    function it_creates_a_collection_with_a_new_game()
    {
        //TODO
    }
}
