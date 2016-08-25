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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', 'text', [
                'label' => 'label.internal_name'
            ])
            ->add('title', 'text', [
                'label' => 'label.title'
            ])
            ->add('body', 'ckeditor', [
                'required' => false,
                'label'    => 'label.body',
            ])
            ->add('publishable', null, [
                'label' => 'label.publishable'
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent'
        ]);
    }

    public function getName()
    {
        return 'app_page';
    }
}
