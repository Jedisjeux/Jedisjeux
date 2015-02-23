<?php

namespace spec\JDJ\CollectionBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CollectionTypeSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\CollectionBundle\Form\CollectionType');
    }


    function it_is_a_form_type()
    {
        $this->shouldImplement('Symfony\Component\Form\FormTypeInterface');
    }

    function it_builds_form_with_proper_fields(FormBuilder $builder)
    {
        $builder
            ->add('name')
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add('description')
            ->shouldBeCalled()
            ->willReturn($builder)
        ;

        $builder
            ->add('user','hidden')
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add('listElements','hidden')
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $this->buildForm($builder, array());
    }


    function it_has_valid_name()
    {
        $this->getName()->shouldReturn('jdj_collectionbundle_collection');
    }
}
