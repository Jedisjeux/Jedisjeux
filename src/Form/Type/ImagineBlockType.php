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

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ImagineBlockType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('image', 'cmf_media_image', [
                'label' => 'label.image',
                'attr' => ['class' => 'imagine-thumbnail'],
                'required' => false,
            ])
            ->add('label', null, [
                'label' => 'label.description',
                'required' => false,
            ])
            ->add('linkUrl', null, [
                'label' => 'label.link_url',
                'required' => false,
            ])
            ->add('_type', HiddenType::class, [
                'data' => 'imagine',
                'label' => 'app.ui.main_image',
                'mapped' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'validation_groups' => $this->validationGroups,
            'cascade_validation' => true,
        ]);
    }

    public function getName()
    {
        return 'app_imagine_block';
    }
}
