<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 15/09/2014
 * Time: 19:16
 */

namespace JDJ\CoreBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CibleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('description')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\CoreBundle\Entity\Cible'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_corebundle_cible';
    }
} 