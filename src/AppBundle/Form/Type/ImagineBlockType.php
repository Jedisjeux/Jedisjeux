<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 26/01/2016
 * Time: 12:57
 */

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ImagineBlockType extends AbstractResourceType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', null, array(
                'label' => 'label.internal_name'
            ))
           ->add('image', 'cmf_media_image', array(
                'label' => 'label.image',
                'attr' => array('class' => 'imagine-thumbnail'),
                'required' => false
            ))
            ->add('label', null, array(
                'label' => 'label.description',
                'required' => false,
            ))
            ->add('linkUrl', null, array(
                'label' => 'label.link_url',
                'required' => false
            ))
            ->add('publishable', null, array(
                'label' => 'label.publishable',
                'required' => false,
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

    public function getName()
    {
        return 'app_imagine_block';
    }
}