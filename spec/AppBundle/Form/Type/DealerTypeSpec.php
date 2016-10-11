<?php

namespace spec\AppBundle\Form\Type;

use AppBundle\Form\Type\DealerType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerTypeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(DealerType::class, ['sylius']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DealerType::class);
    }

    function it_extends_abstract_resource_type()
    {
        $this->shouldHaveType(AbstractResourceType::class);
    }

    function it_has_name()
    {
        $this->getName()->shouldReturn('app_dealer');
    }
}
