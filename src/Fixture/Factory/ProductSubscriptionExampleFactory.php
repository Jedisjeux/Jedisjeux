<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Fixture\Factory;

use App\Entity\ProductSubscription;
use App\Entity\Subscription;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSubscriptionExampleFactory extends AbstractExampleFactory
{
    /** @var FactoryInterface */
    private $productSubscriptionFactory;

    /** @var RepositoryInterface */
    private $productRepository;

    /** @var RepositoryInterface */
    private $customerRepository;

    /** @var \Faker\Generator */
    private $faker;

    /** @var OptionsResolver */
    private $optionsResolver;

    /**
     * @param FactoryInterface    $productSubscriptionFactory
     * @param RepositoryInterface $productRepository
     * @param RepositoryInterface $customerRepository
     */
    public function __construct(
        FactoryInterface $productSubscriptionFactory,
        RepositoryInterface $productRepository,
        RepositoryInterface $customerRepository
    ) {
        $this->productSubscriptionFactory = $productSubscriptionFactory;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;

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

        /** @var Subscription $subscription */
        $subscription = $this->productSubscriptionFactory->createNew();
        $subscription->setSubject($options['subject']);
        $subscription->setSubscriber($options['subscriber']);

        // remove default options
        foreach ($subscription->getOptions() as $option) {
            $subscription->removeOption($option);
        }

        foreach ($options['options'] as $option) {
            $subscription->addOption($option);
        }

        return $subscription;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('options', function (Options $options) {
                return $this->faker->randomElements(ProductSubscription::$defaultOptions, 3);
            })
            ->setAllowedTypes('options', 'array')
            ->setDefault('subscriber', LazyOption::randomOne($this->customerRepository))
            ->setNormalizer('subscriber', LazyOption::findOneBy($this->customerRepository, 'email'))
            ->setDefault('subject', LazyOption::randomOne($this->productRepository))
            ->setNormalizer('subject', LazyOption::findOneBy($this->productRepository, 'code'))
        ;
    }
}
