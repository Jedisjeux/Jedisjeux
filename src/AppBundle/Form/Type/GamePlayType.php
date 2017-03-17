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

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('playedAt', null, [
                'label' => 'label.played_at',
                'required' => false,
                'widget' => 'single_text',
                'widget_addon_append' => [
                    'icon' => 'calendar'
                ],
                'html5' => false,
                'attr' => [
                    'class' => 'date',
                ]
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'label.duration',
                'required' => false,
                'widget_addon_append' => [
                    'icon' => 'time'
                ],
                'help_label' => '(en minutes)',
            ])
            ->add('playerCount', null, [
                'label' => 'label.player_count',
                'required' => false,
            ])
            ->add('images', 'collection', [
                'type' => 'app_game_play_image',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'widget_add_btn' => ['label' => "label.add_image"],
                'show_legend' => false, // dont show another legend of subform
                'options' => [ // options for collection fields
                    'label_render' => false,
                    'horizontal_input_wrapper_class' => "col-lg-8",
                    'widget_remove_btn' => ['label' => "label.remove_this_image"],
                ]
            ])
            ->add('players', 'collection', [
                'type' => 'app_player',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'widget_add_btn' => ['label' => "label.add_player"],
                'show_legend' => false, // dont show another legend of subform
                'options' => [ // options for collection fields
                    'label_render' => false,
                    'horizontal_input_wrapper_class' => "col-lg-8",
                    'widget_remove_btn' => ['label' => "label.remove_this_player"],
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_game_play';
    }
}
