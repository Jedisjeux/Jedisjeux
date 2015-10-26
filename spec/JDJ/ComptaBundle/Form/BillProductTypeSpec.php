<?php

namespace spec\JDJ\ComptaBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;

class BillProductTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\ComptaBundle\Form\BillProductType');
    }

    function it_builds_form_with_proper_fields(FormBuilderInterface $builder)
    {

        $builder
            ->add("product", null, ["label" => "label.products"])
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add("quantity", null, ["label" => "label.quantity"])
            ->shouldBeCalled()
            ->willReturn($builder)
        ;

        $this->buildForm($builder, array());
    }


    function it_has_valid_name()
    {
        $this->getName()->shouldReturn('jdj_comptabundle_bill_product');
    }
}
