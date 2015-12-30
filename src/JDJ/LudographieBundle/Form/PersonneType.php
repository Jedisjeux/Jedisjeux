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
            ->add('nom', null, array(
                'label' => 'label.last_name',
            ))
            ->add('prenom', null, array(
                'label' => 'label.first_name',
            ))
            ->add('siteWeb', null, array(
                'label' => 'label.website',
            ))
            ->add('description', 'ckeditor', array(
                'label' => 'label.description',
            ))
            ->add('image', 'file', array(
                'label' => 'label.image',
            ))
            ->add('country', 'entity', array(
                    'class' => 'AppBundle:Country',
                    'multiple' => false,
                    'expanded' => false,
                    'label' => 'label.country',
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