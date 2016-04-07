<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/04/2016
 * Time: 13:21
 */

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayType extends AbstractResourceType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('playedAt', null, [
                'label' => 'label.played_at',
                'required' => false,
                'widget' => 'single_text',
                'widget_addon_append' => array(
                    'icon' => 'calendar'
                ),
                'html5' => false,
                'attr' => [
                    'class' => 'date',
                ]
            ])
            ->add('duration', null, [
                'label' => 'label.duration',
                'required' => false,
                'widget_addon_append' => array(
                    'icon' => 'time'
                ),
                'attr' => [
                    'class' => 'time',
                ]
            ])
            ->add('playerCount', null, [
                'label' => 'label.player_count',
                'required' => false,
                'widget_addon_append' => array(
                    'icon' => 'user'
                ),
            ])
            ->add('images', 'collection', array(
                'type' => 'app_game_play_image',
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
     * @return string
     */
    public function getName()
    {
        return 'app_game_play';
    }
}