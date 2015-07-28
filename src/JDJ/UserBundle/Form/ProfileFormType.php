<?php

namespace JDJ\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'profile',
        ));
    }

    public function getName()
    {
        return 'jdj_user_profile';
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
        ;
    }
}
