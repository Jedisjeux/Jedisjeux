<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 04/04/16
 * Time: 08:12
 */

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', null, array(
                'label' => 'label.name',
            ))
            ->add('taxons', 'sylius_taxon_selection', array(
                'required' => false,
            ))
            ->add('shortDescription', 'ckeditor', array(
                'label' => 'label.short_description',
            ))
            ->add('description', 'ckeditor', array(
                'label' => 'label.description',
            ))
            ->add('ageMin', null, array(
                'label' => 'label.age_min',
            ))
            ->add('durationMin', null, array(
                'label' => 'label.duration_min',
            ))
            ->add('durationMax', null, array(
                'label' => 'label.duration_max',
            ))
            ->add('joueurMin', null, array(
                'label' => 'label.player_count_min',
            ))
            ->add('joueurMax', null, array(
                'label' => 'label.player_count_max',
            ))
            ->add('images', 'collection', array(
                'type' => 'app_product_variant_image',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'widget_add_btn' => array('label' => "label.add_image"),
                'show_legend' => false, // dont show another legend of subform
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'horizontal_input_wrapper_class' => "col-lg-8",
                    'widget_remove_btn' => array('label' => "label.remove_this_image"),
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_product';
    }
}