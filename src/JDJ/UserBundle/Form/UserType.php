<?php

namespace JDJ\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('avatarFile', 'file', array(
                'mapped' => false,
                'required' => false,
                'label' => "Avatar",
            ))
            ->add('presentation', 'textarea', array(
                'required' => false,
                'label' => "Présentation",
            ))
            ->add('username', 'text', array(
                'label' => "Nom d'utilisateur",
            ))
            ->add('email')
            ->add('dateNaissance', 'date', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
                'label' => "Date de naissance",
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_userbundle_user';
    }
}
