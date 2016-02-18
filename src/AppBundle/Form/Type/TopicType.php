<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 17/02/2016
 * Time: 17:21
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('title', null, array(
                'label' => 'label.title',
            ));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_topic';
    }
}