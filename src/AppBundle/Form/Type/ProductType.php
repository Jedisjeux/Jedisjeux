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
use Doctrine\ORM\EntityRepository;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonAutocompleteChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('firstVariant', ProductVariantType::class, [])
            ->add('mainTaxon', EntityType::class, array(
                'label' => 'label.target_audience',
                'placeholder' => 'label.choose_target_audience',
                'class' => 'AppBundle:Taxon',
                'group_by' => 'parent',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', Taxon::CODE_TARGET_AUDIENCE)
                        ->orderBy('o.position');
                },
                'multiple' => false,
                'required' => false,
            ))
            ->add('mechanisms', EntityType::class, array(
                'label' => 'label.mechanisms',
                'placeholder' => 'label.choose_mechanisms',
                'class' => 'AppBundle:Taxon',
                'group_by' => 'parent',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', Taxon::CODE_MECHANISM)
                        ->orderBy('o.position');
                },
                'multiple' => true,
                'required' => false,
            ))
            ->add('themes', EntityType::class, array(
                'label' => 'label.themes',
                'placeholder' => 'label.choose_themes',
                'class' => 'AppBundle:Taxon',
                'group_by' => 'parent',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', Taxon::CODE_THEME)
                        ->orderBy('o.position');
                },
                'multiple' => true,
                'required' => false,
            ))
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => ProductTranslationType::class,
                'label' => 'sylius.form.product.translations',
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
    public function getBlockPrefix()
    {
        return 'sylius_product';
    }
}
