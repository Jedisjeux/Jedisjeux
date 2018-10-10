<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type\SingleImageBlock;

use App\Document\SingleImageBlock;
use App\Form\Type\SingleImageBlockType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LeftImageBlockType extends SingleImageBlockType
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
                'data' => 'single_image_left',
                'label' => 'app.ui.image_on_the_left_side',
                'mapped' => false,
            ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'single_image_left_block';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_left_image_block';
    }
}
