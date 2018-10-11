<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type\Customer;

use App\Entity\Customer;
use Sylius\Bundle\CustomerBundle\Form\Type\CustomerType as BaseCustomerType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Michał Marcinkowski <michal.marcinkowski@lakion.com>
 */
class CustomerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('gender')
            ->add('firstName', HiddenType::class)
            ->add('lastName', HiddenType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => ['sylius'],
            'data_class' => Customer::class,
        ));
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return BaseCustomerType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_customer';
    }
}
