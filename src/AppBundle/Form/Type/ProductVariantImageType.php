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

use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductVariantImageType extends AbstractImageType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('description', null, array(
                'required' => false,
                'label' => 'label.description',
            ))
            ->add('main', null, array(
                'required' => false,
                'label' => 'label.main',
            ))
            ->add('material', null, array(
                'required' => false,
                'label' => 'label.material',
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_product_variant_image';
    }
}
