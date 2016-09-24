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
use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            ->add('masterVariant', 'sylius_product_variant', [
                'master' => true,
            ])
            ->add('mainTaxon', 'sylius_taxon_choice', array(
                'required' => false,
            ))
            ->add('mechanisms', 'entity', array(
                'label' => 'label.mechanisms',
                'class' => 'AppBundle:Taxon',
                'group_by' => 'parent',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.root IS NOT NULL')
                        ->setParameter('code', Taxon::CODE_MECHANISM)
                        ->orderBy('o.left');
                },
                'expanded' => false,
                'multiple' => true,
                'placeholder' => 'label.choose_mechanisms',
                'required' => false,
            ))
            ->add('themes', 'entity', array(
                'label' => 'label.themes',
                'class' => 'AppBundle:Taxon',
                'group_by' => 'parent',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.root IS NOT NULL')
                        ->setParameter('code', Taxon::CODE_THEME)
                        ->orderBy('o.left');
                },
                'expanded' => false,
                'multiple' => true,
                'placeholder' => 'label.choose_themes',
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
