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

use AppBundle\Entity\Product;
use AppBundle\Entity\Taxon;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonAutocompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', null, array(
                'required' => true,
                'label' => 'label.name',
            ))
            ->add('firstVariant', ProductVariantType::class, [])
            ->add('mainTaxon', TaxonAutocompleteChoiceType::class, array(
                'label' => 'label.target_audience',
                'placeholder' => 'label.choose_target_audience',
                //'root' => Taxon::CODE_TARGET_AUDIENCE,
                'multiple' => false,
                'required' => false,
            ))
            ->add('mechanisms', TaxonAutocompleteChoiceType::class, array(
                'label' => 'label.mechanisms',
                'placeholder' => 'label.choose_mechanisms',
                //'root' => Taxon::CODE_MECHANISM,
                'multiple' => true,
                'required' => false,
            ))
            ->add('themes', TaxonAutocompleteChoiceType::class, array(
                'label' => 'label.themes',
                'placeholder' => 'label.choose_themes',
                //'root' => Taxon::CODE_THEME,
                'multiple' => true,
                'required' => false,
            ))
            ->add('shortDescription', CKEditorType::class, array(
                'required' => false,
                'label' => 'label.short_description',
            ))
            ->add('description', CKEditorType::class, [
                'required' => false,
                'label' => 'label.description',
            ])
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
                'entry_type' => ProductBarcodeType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ))
            ->add('associations', ProductAssociationsType::class, [
                'label' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'cascade_validation' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_product';
    }
}
