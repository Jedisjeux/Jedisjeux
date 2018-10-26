<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class StaticContentType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add('publishable', null, [
                'label' => 'sylius.ui.published',
            ])
            ->add('name', TextType::class, [
                'label' => 'app.ui.internal_name',
            ])
            ->add('title', TextType::class, [
                'label' => 'sylius.form.static_content.title',
            ])
            ->add('body', CKEditorType::class, [
                'required' => false,
                'label' => 'sylius.form.static_content.body',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_static_content';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sylius_static_content';
    }
}
