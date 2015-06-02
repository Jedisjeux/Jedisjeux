<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 02/06/2015
 * Time: 10:40
 */

namespace JDJ\ComptaBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentMethodType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'label.name',
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\ComptaBundle\Entity\PaymentMethod'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_comptabundle_payement_method';
    }
}