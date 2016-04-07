<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 08/04/16
 * Time: 00:16
 */

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PlayerType extends AbstractResourceType
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