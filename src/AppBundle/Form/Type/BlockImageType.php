<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class BlockImageType extends AbstractImageType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('label', TextType::class, [
                'label' => 'app.ui.image_legend',
                'required' => false,
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_block_image';
    }
}
