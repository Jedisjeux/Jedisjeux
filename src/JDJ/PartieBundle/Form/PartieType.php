<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 27/08/2014
 * Time: 23:45
 */

namespace JDJ\PartieBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('jeu')
            ->add('user')
            ->add('playedAt')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\PartieBundle\Entity\Partie'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_partiebundle_partie';
    }
} 