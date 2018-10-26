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

use App\Entity\ProductVariant;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType as BaseProductVariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductVariantType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', TextType::class, [
                'label' => 'label.name',
                'required' => false,
            ])
            ->add('releasedAt', DateType::class, [
                'label' => 'label.release_date',
                'required' => false,
                'years' => range(1902, date('Y') + 2),
            ])
            ->add('releasedAtPrecision', ChoiceType::class, [
                'label' => 'label.release_date_precision',
                'required' => true,
                'choices' => [
                    'label.day' => ProductVariant::RELEASED_AT_PRECISION_ON_DAY,
                    'label.month' => ProductVariant::RELEASED_AT_PRECISION_ON_MONTH,
                    'label.year' => ProductVariant::RELEASED_AT_PRECISION_ON_YEAR,
                ],
            ])
            ->add('images', CollectionType::class, [
                'label' => false,
                'entry_type' => ProductVariantImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('designers', PersonAutocompleteChoiceType::class, [
                'label' => 'label.designers',
                'required' => false,
                'multiple' => true,
            ])
            ->add('artists', PersonAutocompleteChoiceType::class, [
                'label' => 'label.artists',
                'required' => false,
                'multiple' => true,
            ])
            ->add('publishers', PersonAutocompleteChoiceType::class, [
                'label' => 'label.publishers',
                'required' => false,
                'multiple' => true,
            ])
            ->remove('presentation');
    }

    public function getParent()
    {
        return BaseProductVariantType::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sylius_product_variant';
    }
}
