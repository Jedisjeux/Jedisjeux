<?php

namespace spec\JDJ\CollectionBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;

class UserGameAttributeTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\CollectionBundle\Form\UserGameAttributeType');
    }

    function it_builds_form_with_proper_fields(FormBuilderInterface $builder)
    {

        $builder
            ->add('favorite')
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add('owned')
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add('wanted')
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add('played')
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add('user', 'entity', Argument::type('array'))
            ->willReturn($builder);
        $builder
            ->add('jeu', 'entity', Argument::type('array'))
            ->willReturn($builder);

        $this->buildForm($builder, array());
    }


    function it_has_valid_name()
    {
        $this->getName()->shouldReturn('jdj_collectionbundle_usergameattribute');
    }
}
