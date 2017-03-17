<?php

/*
 * This file is part of jedisjeux project.
 *
 * (c) Loïc Frémont
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
class RedirectionType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('source', TextType::class, [
                'label' => 'app.ui.source',
            ])
            ->add('destination', TextType::class, [
                'label' => 'app.ui.destination',
            ])
            ->add('permanent', CheckboxType::class, [
                'label' => 'app.ui.permanent',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_redirection';
    }
}
