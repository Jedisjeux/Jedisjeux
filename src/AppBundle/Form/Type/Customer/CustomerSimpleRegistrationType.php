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

use AppBundle\Form\EventSubscriber\CustomerRegistrationFormSubscriber;
use AppBundle\Form\EventSubscriber\UserRegistrationFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Michał Marcinkowski <michal.marcinkowski@lakion.com>
 */
class CustomerSimpleRegistrationType extends AbstractResourceType
{
    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @param string              $dataClass
     * @param array               $validationGroups
     * @param RepositoryInterface $customerRepository
     */
    public function __construct($dataClass, array $validationGroups, RepositoryInterface $customerRepository)
    {
        parent::__construct($dataClass, $validationGroups);

        $this->customerRepository = $customerRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add('email', 'email', [
                'label' => 'sylius.form.customer.email',
            ])
            ->add('user', 'sylius_shop_user_registration', [
                'label' => false,
            ])
            ->addEventSubscriber(new CustomerRegistrationFormSubscriber($this->customerRepository))
            ->setDataLocked(false)
        ;
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

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_customer_simple_registration';
    }
}
