<?php

namespace spec\AppBundle\Form\Type;

use AppBundle\Form\Type\DealerType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\AbstractType;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DealerType::class);
    }

    function it_extends_abstract_resource_type()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    function it_has_name()
    {
        $this->getName()->shouldReturn('app_dealer');
    }
}
