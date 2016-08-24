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

use AppBundle\Document\SingleImageBlock;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('name', null, array(
                'label' => 'label.internal_name'
            ))
            ->add('title', null, array(
                'label' => 'label.title'
            ))
            ->add('body', TextareaType::class, array(
                'label' => 'label.body',
            ))
            ->add('imagePosition', ChoiceType::class, array(
                'label' => 'label.image_position',
                'required' => false,
                'choices' => [
                    'label.on_top' => SingleImageBlock::POSITION_TOP,
                    'label.on_the_left_side' => SingleImageBlock::POSITION_LEFT,
                    'label.on_the_right_side' => SingleImageBlock::POSITION_RIGHT,
                ],
                'choices_as_values' => true,
            ))
            ->add('class', null, array(
                'label' => 'label.css_class',
                'required' => false,
            ))
            ->add('children', CollectionType::class, [
                'type' => 'app_imagine_block',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'cascade_validation' => true,
            ])
            ->add('_type', HiddenType::class, [
                'data' => 'single_image',
                'mapped' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
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
