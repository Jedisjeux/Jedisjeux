<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\Factory;

use App\Entity\Notification;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class NotificationExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $notificationFactory;

    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    public function __construct(
        FactoryInterface $notificationFactory,
        RepositoryInterface $customerRepository
    ) {
        $this->notificationFactory = $notificationFactory;
        $this->customerRepository = $customerRepository;

        $this->faker = \Faker\Factory::create('fr_FR');
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var Notification $notification */
        $notification = $this->notificationFactory->createNew();
        $notification->setRead($options['read']);
        $notification->setMessage($options['message']);
        $notification->setTarget($options['target']);
        $notification->setRecipient($options['recipient']);

        foreach ($options['authors'] as $author) {
            $notification->addAuthor($author);
        }

        return $notification;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('read', function (Options $options) {
                return $this->faker->boolean(10);
            })
            ->setAllowedTypes('read', ['bool'])

            ->setDefault('message', function (Options $options) {
                return $this->faker->sentence();
            })

            ->setDefault('target', function (Options $options) {
                return $this->faker->url;
            })

            ->setDefault('recipient', LazyOption::randomOne($this->customerRepository))
            ->setAllowedTypes('recipient', ['null', 'string', CustomerInterface::class])
            ->setNormalizer('recipient', LazyOption::findOneBy($this->customerRepository, 'email'))

            ->setDefault('authors', LazyOption::randomOnes($this->customerRepository, 3))
            ->setAllowedTypes('authors', 'array')
            ->setNormalizer('authors', LazyOption::findBy($this->customerRepository, 'email'));
    }
}
