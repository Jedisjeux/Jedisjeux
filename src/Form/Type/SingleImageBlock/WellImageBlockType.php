<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type\SingleImageBlock;

use App\Form\Type\SingleImageBlockType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class WellImageBlockType extends SingleImageBlockType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('imagePosition')
            ->remove('class')
            ->add('_type', HiddenType::class, [
                'data' => 'single_image_well',
                'label' => 'app.ui.framed',
                'mapped' => false,
            ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'single_image_well_block';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_well_image_block';
    }
}
