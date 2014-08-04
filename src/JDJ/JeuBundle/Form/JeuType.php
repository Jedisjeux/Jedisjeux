<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/06/2014
 * Time: 18:43
 */

namespace JDJ\JeuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JeuType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('ageMin')
            ->add('intro')
            ->add('materiel')
            ->add('but')
            ->add('description')
            ->add('joueurMin')
            ->add('joueurMax')
            ->add('mecanismes', 'entity', array(
                    'class' => 'JDJJeuBundle:Mecanisme',
                    'multiple' => true,
                    'expanded' => false
                )
            )
            ->add('themes', 'entity', array(
                    'class' => 'JDJJeuBundle:Theme',
                    'multiple' => true,
                    'expanded' => false
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\JeuBundle\Entity\Jeu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_jeubundle_jeu';
    }
} 