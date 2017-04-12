<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type\Customer;

use Sylius\Bundle\CustomerBundle\Form\Type\CustomerType as BaseCustomerType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Michał Marcinkowski <michal.marcinkowski@lakion.com>
 */
class CustomerType extends AbstractResourceType
{
    /**
     * @var EventSubscriberInterface
     */
    private $addUserFormSubscriber;

    /**
     * @param string $dataClass
     * @param string[] $validationGroups
     * @param EventSubscriberInterface $addUserFormSubscriber
     */
    public function __construct(
        $dataClass,
        array $validationGroups = [],
        EventSubscriberInterface $addUserFormSubscriber
    ) {
        parent::__construct($dataClass, $validationGroups);
        $this->addUserFormSubscriber = $addUserFormSubscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('gender')
            ->add('phoneNumber', TextType::class, [
                'label' => 'sylius.ui.phone_number',
                'required' => false,
            ])
            ->add('firstName', HiddenType::class, [
                'data' => 'John',
            ])
            ->add('lastName', HiddenType::class, [
                'data' => 'Doe',
            ]);

        $builder
            ->addEventSubscriber($this->addUserFormSubscriber);
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'validation_groups' => $this->validationGroups,
            'cascade_validation' => true,
        ]);
    }
}
