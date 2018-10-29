<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use App\Entity\Block;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SingleImageBlockType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add('title', null, [
                'label' => 'label.title',
            ])
            ->add('name', HiddenType::class)
            ->add('body', CKEditorType::class, [
                'label' => 'label.body',
            ])
            ->add('imagePosition', ChoiceType::class, [
                'label' => 'label.image_position',
                'required' => false,
                'choices' => [
                    'label.on_top' => Block::POSITION_TOP,
                    'label.on_the_left_side' => Block::POSITION_LEFT,
                    'label.on_the_right_side' => Block::POSITION_RIGHT,
                ],
            ])
            ->add('class', null, [
                'label' => 'label.css_class',
                'required' => false,
            ])
            ->add('imagineBlock', 'app_imagine_block', [
                'label' => 'sylius.ui.image',
            ])
            ->add('_type', HiddenType::class, [
                'data' => 'single_image',
                'mapped' => false,
            ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'single_image_block';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('model_class', $this->dataClass);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_single_image_block';
    }
}
