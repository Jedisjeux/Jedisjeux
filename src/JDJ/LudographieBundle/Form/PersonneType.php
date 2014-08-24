<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/06/2014
 * Time: 18:43
 */

namespace JDJ\LudographieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonneType extends AbstractType
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
            ->add('siteWeb')
            ->add('description')
            ->add('image')
            ->add('pays', 'entity', array(
                    'class' => 'JDJWebBundle:Pays',
                    'multiple' => false,
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
            'data_class' => 'JDJ\LudographieBundle\Entity\Personne'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_ludographiebundle_personne';
    }
} 