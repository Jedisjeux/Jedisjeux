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

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PriceListType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('active', CheckboxType::class, [
                'label' => 'label.active',
            ])
            ->add('path', TextType::class, [
                'label' => 'label.path',
            ])
            ->add('headers', CheckboxType::class, [
                'label' => 'label.headers',
            ])
            ->add('delimiter', TextType::class, [
                'label' => 'app.ui.delimiter',
            ])
            ->add('utf8', CheckboxType::class, [
                'label' => 'app.ui.utf8',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_price_list';
    }
}
