<?php

namespace JDJ\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserRegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('avatar')
            ->add('presentation', 'ckeditor', array(
                'required' => false,
            ))
            ->add('username')
            ->add('email')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Vérification'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('dateNaissance', 'date', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
            ))
        ;
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_user_registration';
    }
}
