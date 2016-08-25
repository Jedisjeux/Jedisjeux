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

use Infinite\FormBundle\Form\Type\PolyCollectionType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleContentType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', null, [
                'label' => 'label.internal_name',
            ])
            ->add('title', null, [
                'label' => 'label.title',
            ])
            ->add('children', PolyCollectionType::class, [
                'types' => [
                    'app_single_image_block',
                    'app_blockquote_block',
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'cascade_validation' => true,
            ])
            ->add('publishable', null, [
                'label' => 'label.publishable'
            ])
            ->add('publishStartDate', 'datetime', [
                'label' => 'label.start_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'datetime',
                )
            ])
            ->add('publishEndDate', 'datetime', [
                'label' => 'label.end_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'datetime',
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_article_content';
    }
}
