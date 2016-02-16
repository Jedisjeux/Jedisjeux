<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 30/08/2014
 * Time: 17:38
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameReviewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("rate", "app_game_rate")
            ->add('title')
            ->add('body', 'ckeditor');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\GameReview',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_game_review';
    }
} 