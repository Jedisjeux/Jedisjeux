<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 07/06/2014
 * Time: 21:27
 */

namespace JDJ\JeuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MechanismType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', 'ckeditor')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\JeuBundle\Entity\Mechanism'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_jeubundle_mecanisme';
    }
} 