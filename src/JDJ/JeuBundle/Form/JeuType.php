<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/06/2014
 * Time: 18:43
 */

namespace JDJ\JeuBundle\Form;

use JDJ\JeuBundle\Entity\Jeu;
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
            ->add('name', null, array(
                'label' => 'label.name',
            ))
            ->add('ageMin', null, array(
                'required' => false,
            ))
            ->add('joueurMin', null, array(
                'required' => false,
            ))
            ->add('joueurMax', null, array(
                'required' => false,
            ))
            ->add('mechanisms', 'entity', array(
                    'class' => 'JDJJeuBundle:Mechanism',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false,
                )
            )
            ->add('themes', 'entity', array(
                    'class' => 'JDJJeuBundle:Theme',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false,
                )
            )
            ->add('cible', 'entity', array(
                    'class' => 'JDJCoreBundle:Cible',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                )
            )
            ->add('intro', 'ckeditor', array(
                'required' => false,
            ))
            ->add('but', 'ckeditor', array(
                'required' => false,
            ))
            ->add('description', 'ckeditor', array(
                'required' => false,
            ))
            ->add('status', 'choice', array(
                'choices'   => Jeu::getStatusList(),
                'required'  => true,
            ))
            ->add('materiel', null, array(
                'required' => false,
            ));
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