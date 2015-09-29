<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 03/06/2015
 * Time: 13:27
 */

namespace JDJ\ComptaBundle\Form\Bill;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paidAt', null, array(
                'label' => 'label.paid_at',
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\ComptaBundle\Entity\Bill'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_comptabundle_bill_payment';
    }
}