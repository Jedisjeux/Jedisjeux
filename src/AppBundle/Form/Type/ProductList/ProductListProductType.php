<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type\ProductList;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductListProductType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('products', EntityType::class, [
                'label' => 'sylius.ui.products',
                'class' => ProductInterface::class,
                'multiple' => true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_product_list_product';
    }
}
