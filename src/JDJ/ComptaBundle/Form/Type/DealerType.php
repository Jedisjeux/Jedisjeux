<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 14/12/2015
 * Time: 11:45
 */

namespace JDJ\ComptaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerType extends AbstractType
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
            ->add('address', 'jdj_comptabundle_address', array(
                'label' => 'label.address'
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\ComptaBundle\Entity\Dealer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_dealer';
    }
}