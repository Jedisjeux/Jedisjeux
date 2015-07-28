<?php

namespace spec\JDJ\UserBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;

class UserTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\UserBundle\Form\UserType');
    }

    function it_builds_form_with_proper_fields(FormBuilderInterface $builder)
    {

        $builder
            ->add("avatarFile", "file", ["mapped" => false, "required" => false, "label" => "Avatar"])
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add("presentation", "textarea", ["required" => false, "label" => "Présentation"])
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add("username", "text", ["label" => "Nom d'utilisateur"])
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add("email")
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add("dateNaissance", "date", ["input" => "datetime", "widget" => "single_text", "label" => "Date de naissance"])
            ->willReturn($builder)
            ->shouldBeCalled()
        ;


        $this->buildForm($builder, array());
    }


    function it_has_valid_name()
    {
        $this->getName()->shouldReturn('jdj_userbundle_user');
    }
}
