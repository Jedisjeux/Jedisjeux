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

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductTranslationType as BaseProductTranslationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductTranslationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('description')
            ->add('shortDescription', CKEditorType::class, [
               'required' => false,
                'label' => 'app.ui.short_description',
            ])
            ->add('description', CKEditorType::class, [
                'required' => false,
                'label' => 'sylius.ui.description',
            ])
            ->add('boxContent', TextareaType::class, [
                'required' => false,
                'label' => 'app.ui.box_content',
            ])
            ->remove('metaKeywords')
            ->remove('metaDescription');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseProductTranslationType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sylius_product_translation';
    }
}
