<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 26/01/2016
 * Time: 17:37
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SlideshowBlockType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'sylius.form.slideshow_block.internal_name'
            ))
            ->add('title', 'text', array(
                'label' => 'sylius.form.slideshow_block.title'
            ))
            ->add('children', 'collection', array(
                'type' => new ImagineBlockType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => false,
                'widget_add_btn' => array('label' => "label.add_slide"),
                'cascade_validation' => true,
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'horizontal_input_wrapper_class' => "col-lg-8",
                    'widget_remove_btn' => array('label' => "label.remove_this_slide"),
                )
            ))
            ->add('publishable', null, array(
                'label' => 'label.publishable'
            ))
            ->add('publishStartDate', 'datetime', array(
                'label' => 'label.publish_start_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'datetime',
                )
            ))
            ->add('publishEndDate', 'datetime', array(
                'label' => 'label.publish_end_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'datetime',
                )
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock'
        ));
    }

    public function getName()
    {
        return 'app_slideshow_block';
    }
}