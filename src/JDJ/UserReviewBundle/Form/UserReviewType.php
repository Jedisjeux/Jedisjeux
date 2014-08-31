<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 30/08/2014
 * Time: 17:38
 */

namespace JDJ\UserReviewBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserReviewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("jeuNote", "jdj_userreviewbundle_jeunote")
            ->add('libelle')
            ->add('body')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JDJ\UserReviewBundle\Entity\UserReview',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jdj_userreviewbundle_userreview';
    }
} 