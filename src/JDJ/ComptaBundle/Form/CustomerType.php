<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 21/05/2015
 * Time: 11:08
 */

namespace JDJ\ComptaBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class CustomerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('society', null, array(
                'label' => 'label.society',
            ))
            ->add('email', 'email', array(
                'label' => 'label.email',
            ))
            ->add('address', 'jdj_comptabundle_address', array(
                'label' => 'label.address'
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\ComptaBundle\Entity\Customer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_comptabundle_customer';
    }
}