<?php

namespace spec\App\Entity;

use App\Entity\AbstractImage;
use App\Entity\Person;
use App\Entity\PersonImage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PersonImageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PersonImage::class);
    }

    function it_extends_abstract_image()
    {
        $this->shouldBeAnInstanceOf(AbstractImage::class);
    }

    function it_has_no_description_by_default()
    {
        $this->getDescription()->shouldReturn(null);
    }

    function its_description_is_mutable()
    {
        $this->setDescription("Awesome");
        $this->getDescription()->shouldReturn("Awesome");
    }

    function it_has_no_person_by_default()
    {
        $this->getPerson()->shouldReturn(null);
    }

    function its_person_is_mutable(Person $person)
    {
        $this->setPerson($person);
        $this->getPerson()->shouldReturn($person);
    }

    function it_is_not_a_main_image_by_default()
    {
        $this->isMain()->shouldReturn(false);
    }
}
