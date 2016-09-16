<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('code', null, [
                'label' => 'label.code',
            ])
            ->add('name', null, [
                'label' => 'label.name',
            ])
            ->add('image', 'app_dealer_image', [
                'label' => 'label.image',
                'required' => false,
            ])
            ->add('priceList', 'app_price_list', [
                'label' => 'label.price_list',
                'required' => false,
            ])
            ->add('pubBanners', 'collection', array(
                'label' => 'label.pub_banners',
                'type' => 'app_pub_banner',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'widget_add_btn' => array('label' => "label.add_image"),
                'show_legend' => false, // dont show another legend of subform
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'horizontal_input_wrapper_class' => "col-lg-8",
                    'widget_remove_btn' => array('label' => "label.remove_this_image"),
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_dealer';
    }
}
