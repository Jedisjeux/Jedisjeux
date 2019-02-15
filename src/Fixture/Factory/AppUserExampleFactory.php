<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\Factory;

use App\Entity\Avatar;
use App\Entity\Customer;
use App\Entity\User;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class AppUserExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $appUserFactory;

    /**
     * @var FactoryInterface
     */
    private $customerFactory;

    /**
     * @var FactoryInterface
     */
    private $avatarFactory;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * AppUserExampleFactory constructor.
     *
     * @param FactoryInterface $appUserFactory
     * @param FactoryInterface $customerFactory
     * @param FactoryInterface $avatarFactory
     */
    public function __construct(
        FactoryInterface $appUserFactory,
        FactoryInterface $customerFactory,
        FactoryInterface $avatarFactory
    ) {
        $this->appUserFactory = $appUserFactory;
        $this->customerFactory = $customerFactory;
        $this->avatarFactory = $avatarFactory;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var Customer $customer */
        $customer = $this->customerFactory->createNew();
        $customer->setEmail($options['email']);
        $customer->setFirstName($options['first_name']);
        $customer->setLastName($options['last_name']);

        if (null !== $options['avatar']) {
            $this->createAvatar($customer, $options);
        }

        /** @var User $user */
        $user = $this->appUserFactory->createNew();
        $user->setUsername($options['username']);
        $user->setPlainPassword($options['password']);
        $user->setEnabled($options['enabled']);
        $user->addRole('ROLE_USER');

        foreach ($options['roles'] as $role) {
            $user->addRole($role);
        }

        $user->setCustomer($customer);

        return $user;
    }

    /**
     * @param Customer $customer
     * @param array    $options
     */
    private function createAvatar(Customer $customer, array $options)
    {
        $imagePath = $options['avatar'];

        /** @var Avatar $avatar */
        $avatar = $this->avatarFactory->createNew();
        $avatar->setPath(basename($imagePath));

        file_put_contents($avatar->getAbsolutePath(), file_get_contents($imagePath));

        $customer->setAvatar($avatar);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('username', function (Options $options) {
                return $this->faker->userName;
            })

            ->setDefault('email', function (Options $options) {
                return $this->faker->email;
            })

            ->setDefault('first_name', function (Options $options) {
                return $this->faker->firstName;
            })

            ->setDefault('last_name', function (Options $options) {
                return $this->faker->lastName;
            })

            ->setDefault('enabled', true)
            ->setAllowedTypes('enabled', 'bool')

            ->setDefault('password', 'password123')

            ->setDefault('roles', [])
            ->setAllowedTypes('roles', 'array')

            ->setDefault('avatar', LazyOption::randomOneImageOrNull(
                __DIR__.'/../../../tests/Resources/fixtures/avatars', 50
            ))
        ;
    }
}
