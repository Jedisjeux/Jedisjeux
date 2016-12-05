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
class LeftImageBlockType extends SingleImageBlockType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('imagePosition', HiddenType::class, array(
                'data' => SingleImageBlock::POSITION_LEFT,
            ))
            ->remove('class')
            ->add('_type', HiddenType::class, [
                'data' => 'single_image_left',
                'mapped' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_single_image_block_left';
    }
}
