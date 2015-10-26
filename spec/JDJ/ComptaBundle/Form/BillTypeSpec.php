<?php

namespace spec\JDJ\ComptaBundle\Form;

use JDJ\ComptaBundle\Form\BillProductType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

class BillTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('JDJ\ComptaBundle\Form\BillType');
    }

    function it_builds_form_with_proper_fields(FormBuilderInterface $builder)
    {

        $builder
            ->add("customer", null, ["label" => "label.customer"])
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add("billProducts", "collection", ["type" => new BillProductType(), "allow_add" => true, "allow_delete" => true, "by_reference" => false, "prototype" => true, "widget_add_btn" => ["label" => "label.add_product"], "show_legend" => false, "options" => ["label_render" => false, "horizontal_input_wrapper_class" => "col-lg-8", "widget_remove_btn" => ["label" => "label.remove_this_product"]]])
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add("paymentMethod", null, ["label" => "label.payment_method"])
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add("paidAt", "date", ["label" => "label.paid_at", "widget" => "single_text", "format" => "yyyy-MM-dd", "html5" => false, "required" => false])
            ->shouldBeCalled()
            ->willReturn($builder)
        ;
        $builder
            ->add("paidAt", "date", ["label" => "label.paid_at", "widget" => "single_text", "format" => "yyyy-MM-dd", "html5" => false, "required" => false])
            ->shouldBeCalled()
            ->willReturn($builder)
        ;

        $builder
            ->addEventListener(FormEvents::POST_SUBMIT, Argument::type('\Closure'))
            ->willReturn($builder);

        $this->buildForm($builder, array());

    }
}
