<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 18/02/2016
 * Time: 09:16
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('body', 'ckeditor', array(
                'label' => 'label.body',
            ));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_post';
    }
}