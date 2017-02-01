<?php

/*
 * This file is part of Alceane.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type\Customer;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CustomerProfileType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'sylius.ui.email',
            ])
            ->add('avatar', 'app_avatar', [
                'label' => false,
                'required' => false,
            ])
            ->add('firstName', HiddenType::class, [
                'data' => 'John',
            ])
            ->add('lastName', HiddenType::class, [
                'data' => 'Doe',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_customer_profile';
    }
}
