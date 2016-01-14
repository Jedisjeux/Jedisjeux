<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 14/01/2016
 * Time: 14:11
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SimpleBlockType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', 'text', array(
                'label' => 'label.internal_name'
            ))
            ->add('title', 'text', array(
                'label' => 'label.title'
            ))
            ->add('body', 'ckeditor', array(
                'required' => false,
                'label'    => 'label.body',
            ))
            ->add('publishable', null, array(
                'label' => 'label.publishable'
            ))
            ->add('publishStartDate', 'datetime', array(
                'label' => 'label.publish_start_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'datetime',
                )
            ))
            ->add('publishEndDate', 'datetime', array(
                'label' => 'label.publish_end_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'datetime',
                )
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock'
        ));
    }

    public function getName()
    {
        return 'app_block_simple';
    }
}