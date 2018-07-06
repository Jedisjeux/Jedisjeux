<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type\Collection;

use AppBundle\Form\Type\ImagineBlockType as BaseImagineBlockType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ImagineBlockCollectionType extends BaseImagineBlockType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('name')
            ->remove('linkUrl')
            ->remove('publishable')
            ->remove('publishStartDate')
            ->remove('publishEndDate');
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_collection_imagine_block';
    }
}