<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Taxon;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('firstVariant', 'sylius_product_variant', [])
            ->add('mainTaxon', 'sylius_taxon_choice', array(
                'label' => 'label.target_audience',
                'placeholder' => 'label.choose_target_audience',
                'root' => Taxon::CODE_TARGET_AUDIENCE,
                'multiple' => false,
                'required' => false,
            ))
            ->add('mechanisms', 'sylius_taxon_choice', array(
                'label' => 'label.mechanisms',
                'placeholder' => 'label.choose_mechanisms',
                'root' => Taxon::CODE_MECHANISM,
                'multiple' => true,
                'required' => false,
            ))
            ->add('themes', 'sylius_taxon_choice', array(
                'label' => 'label.themes',
                'placeholder' => 'label.choose_themes',
                'root' => Taxon::CODE_THEME,
                'multiple' => true,
                'required' => false,
            ))
            ->add('shortDescription', 'ckeditor', array(
                'label' => 'label.short_description',
            ))
            ->add('description', 'ckeditor', array(
                'label' => 'label.description',
            ))
            ->add('materiel', TextareaType::class, array(
                'required' => false,
                'label' => 'label.material',
            ))
            ->add('ageMin', null, array(
                'label' => 'label.age_min',
            ))
            ->add('durationMin', null, array(
                'label' => 'label.min',
            ))
            ->add('durationMax', null, array(
                'label' => 'label.max',
            ))
            ->add('joueurMin', null, array(
                'label' => 'label.min',
            ))
            ->add('joueurMax', null, array(
                'label' => 'label.max',
            ))
            ->add('barcodes', CollectionType::class, array(
                'label' => 'label.barcodes',
                'type' => 'app_product_barcode',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'widget_add_btn' => array('label' => "label.add_barcode"),
                'show_legend' => false, // dont show another legend of subform
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'horizontal_input_wrapper_class' => "col-lg-8",
                    'widget_remove_btn' => array('label' => "label.remove_this_barcode"),
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
