<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Filter;

use AppBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
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
            ->add('query', 'text', array(
                'label' => 'label.search',
                'required' => false,
            ))
            ->add('status', 'choice', array(
                'label' => 'label.show_only_suggestions_with_status',
                'required' => false,
                'choices' => [
                    'label.new' => Product::STATUS_NEW,
                    'label.need_a_translation' => Product::NEED_A_TRANSLATION,
                    'label.need_a_review' => Product::NEED_A_REVIEW,
                    'label.ready_to_publish' => Product::READY_TO_PUBLISH,
                    'label.published' => Product::PUBLISHED,
                ],
                'choices_as_values' => true,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => null,
                'criteria' => null,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_filter_product';
    }
}
