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
            ->add('name', null, [
                'label' => 'label.internal_name'
            ])
           ->add('image', 'cmf_media_image', [
                'label' => 'label.image',
                'attr' => ['class' => 'imagine-thumbnail'],
                'required' => false
            ])
            ->add('label', null, [
                'label' => 'label.description',
                'required' => false,
            ])
            ->add('linkUrl', null, [
                'label' => 'label.link_url',
                'required' => false
            ])
            ->add('publishable', null, [
                'label' => 'label.publishable',
                'required' => false,
            ])
            ->add('publishStartDate', 'datetime', [
                'label' => 'label.publish_start_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'datetime',
                ]
            ])
            ->add('publishEndDate', 'datetime', [
                'label' => 'label.publish_end_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'datetime',
                ]
            ]);
    }

    public function getName()
    {
        return 'app_imagine_block';
    }
}
