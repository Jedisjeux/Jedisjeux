<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type\SingleImageBlock;

use AppBundle\Document\SingleImageBlock;
use AppBundle\Form\Type\SingleImageBlockType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class RightImageBlockType extends SingleImageBlockType
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
                'data' => 'single_image_right',
                'label' => 'app.ui.image_on_the_right_side',
                'mapped' => false,
            ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'single_image_right_block';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_right_image_block';
    }
}
