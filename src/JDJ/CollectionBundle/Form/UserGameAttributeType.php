<?php

namespace JDJ\CollectionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserGameAttributeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('jeu', 'entity', array(
                    'class' => 'JDJJeuBundle:Jeu',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                )
            )
            ->add('user', 'entity', array(
                    'class' => 'JDJUserBundle:User',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                )
            )
            ->add('favorite')
            ->add('owned')
            ->add('wanted')
            ->add('played')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\CollectionBundle\Entity\UserGameAttribute'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_collectionbundle_usergameattribute';
    }

}
