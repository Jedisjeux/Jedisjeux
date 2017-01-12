<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\AppBundle\Form\Type;

use AppBundle\Entity\ContactRequest;
use AppBundle\Form\Type\ContactRequestType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ContactRequestTypeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(ContactRequest::class, ['sylius']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContactRequestType::class);
    }

    function it_extends_abstract_resource_type()
    {
        $this->shouldHaveType(AbstractResourceType::class);
    }

    function it_has_name()
    {
        $this->getName()->shouldReturn('app_contact_request');
    }

    function it_builds_form(FormBuilderInterface $builder)
    {
        $builder->add('firstName', TextType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('lastName', TextType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('email', EmailType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('body', TextareaType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $this->buildForm($builder, []);
    }
}
