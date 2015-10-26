<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 20/05/2015
 * Time: 15:12
 */

namespace JDJ\ComptaBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class ProductType extends AbstractType
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
            ->add('price', 'money', array(
                'label' => 'label.price',
            ))
            ->add('subscriptionDuration', null, array(
                'label' => 'label.subscription_duration',
                'widget_addon_append' => array(
                    'text' => 'mois',
                ),
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\ComptaBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_comptabundle_product';
    }
}