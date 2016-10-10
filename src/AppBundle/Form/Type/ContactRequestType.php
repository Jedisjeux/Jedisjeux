<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ContactRequestType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('firstName', TextType::class, [
                'label' => 'label.first_name',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'label.last_name',
            ])
            ->add('email', TextType::class, [
                'label' => 'label.email',
            ])
            ->add('body', TextareaType::class, [
                'label' => 'label.body',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_contact_request';
    }
}
