<?php

namespace JDJ\ComptaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BillProductType
 * @package JDJ\ComptaBundle\Form
 */
class BillProductType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', null, array(
                'label' => 'label.products',
            ))
            ->add('quantity', null, array(
                'label' => 'label.quantity',
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\ComptaBundle\Entity\BillProduct'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_comptabundle_bill_product';
    }
}