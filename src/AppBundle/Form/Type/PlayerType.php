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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PlayerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'label.name',
                'required' => false,
                'widget_addon_append' => array(
                    'icon' => 'user'
                ),
            ))
            ->add('score', null, array(
                'label' => 'label.score',
                'widget_addon_append' => array(
                    'text' => 'point(s)'
                ),
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_player';
    }
}
