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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('name', TextType::class, [
                'label' => 'label.internal_name',
            ])
            ->add('title', TextType::class, [
                'label' => 'label.title',
            ])
            ->add('mainImage', 'app_imagine_block', [
                'label' => 'app.ui.main_image',
            ])
            ->add('blocks', PolyCollectionType::class, [
                'types' => [
                    'app_blockquote_block',
                    'app_left_image_block',
                    'app_right_image_block',
                    'app_top_image_block',
                    'app_well_image_block',
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('slideShowBlock', 'app_slideshow_block', [
                'label' => false,
                'required' => false,
            ])
            ->add('publishable', null, [
                'label' => 'label.publishable'
            ])
            ->add('publishStartDate', DateTimeType::class, [
                'label' => 'label.start_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'datetime',
                )
            ])
            ->add('publishEndDate', DateTimeType::class, [
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'validation_groups' => $this->validationGroups,
            'cascade_validation' => true,
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
