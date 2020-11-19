<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Filter;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextType::class, [
                'label' => 'label.search',
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'label.show_only_suggestions_with_status',
                'required' => false,
                'choices' => [
                    'label.new' => Product::STATUS_NEW,
                    'label.pending_translation' => Product::PENDING_TRANSLATION,
                    'label.pending_review' => Product::PENDING_REVIEW,
                    'label.pending_publication' => Product::PENDING_PUBLICATION,
                    'label.published' => Product::PUBLISHED,
                ],
                'choices_as_values' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
                'criteria' => null,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_filter_product';
    }
}
