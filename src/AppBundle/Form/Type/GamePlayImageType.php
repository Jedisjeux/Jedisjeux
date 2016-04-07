<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/04/2016
 * Time: 13:25
 */

namespace AppBundle\Form\Type;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayImageType extends AbstractImageType
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
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_game_play_image';
    }
}