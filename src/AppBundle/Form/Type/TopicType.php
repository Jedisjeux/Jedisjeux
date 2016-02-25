<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 17/02/2016
 * Time: 17:21
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('title', null, array(
                'label' => 'label.title',
            ))
            ->add('mainPost', 'app_post',  array(
                'label' => false,
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Topic'
        ));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_topic';
    }
}