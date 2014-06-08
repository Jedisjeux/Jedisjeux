<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 08/06/2014
 * Time: 14:31
 */

namespace JDJ\JeuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MaterielType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\JeuBundle\Entity\Materiel'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_jeubundle_materiel';
    }
} 