<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use App\Entity\Product;
use App\Entity\Taxon;
use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
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
            ->add('mainTaxon', EntityType::class, [
                'label' => 'label.target_audience',
                'placeholder' => 'label.choose_target_audience',
                'class' => 'App:Taxon',
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
            ])
            ->add('mechanisms', EntityType::class, [
                'label' => 'app.ui.mechanisms',
                'placeholder' => 'label.choose_mechanisms',
                'class' => 'App:Taxon',
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
            ])
            ->add('themes', EntityType::class, [
                'label' => 'app.ui.themes',
                'placeholder' => 'label.choose_themes',
                'class' => 'App:Taxon',
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
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => ProductTranslationType::class,
                'label' => 'sylius.form.product.translations',
            ])
            ->add('minAge', null, [
                'label' => 'app.ui.age',
            ])
            ->add('minDuration', null, [
                'label' => 'app.ui.min',
            ])
            ->add('maxDuration', null, [
                'label' => 'app.ui.max',
            ])
            ->add('minPlayerCount', null, [
                'label' => 'app.ui.min',
            ])
            ->add('maxPlayerCount', null, [
                'label' => 'app.ui.max',
            ])
            ->add('barcodes', CollectionType::class, [
                'label' => 'label.barcodes',
                'entry_type' => ProductBarcodeType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ])
            ->add('associations', ProductAssociationsType::class, [
                'label' => false,
            ])
            ->add('videos', CollectionType::class, [
                'label' => false,
                'entry_type' => ProductVideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('yearAwards', CollectionType::class, [
                'label' => false,
                'entry_type' => YearAwardType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'validation_groups' => ['sylius'],
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
