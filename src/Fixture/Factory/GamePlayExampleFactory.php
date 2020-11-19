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

use App\Entity\GamePlay;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $gamePlayFactory;

    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var RepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * GamePlayExampleFactory constructor.
     */
    public function __construct(FactoryInterface $gamePlayFactory, RepositoryInterface $customerRepository, RepositoryInterface $productRepository)
    {
        $this->gamePlayFactory = $gamePlayFactory;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;

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

        /** @var GamePlay $gamePlay */
        $gamePlay = $this->gamePlayFactory->createNew();
        $gamePlay->setDuration($options['duration']);
        $gamePlay->setPlayerCount($options['player_count']);
        $gamePlay->setPlayedAt($options['played_at']);
        $gamePlay->setProduct($options['product']);
        $gamePlay->setAuthor($options['author']);

        return $gamePlay;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('player_count', function (Options $options) {
                return $this->faker->numberBetween(2, 8);
            })

            ->setDefault('duration', function (Options $options) {
                return $this->faker->randomElement([45, 60, 90, 180]);
            })

            ->setDefault('played_at', function (Options $options) {
                return $this->faker->dateTimeBetween('-1 year', 'yesterday');
            })
            ->setAllowedTypes('played_at', ['null', 'string', \DateTimeInterface::class])
            ->setNormalizer('played_at', function (Options $options, $playedAt) {
                if (!is_string($playedAt)) {
                    return $playedAt;
                }

                return new \DateTime($playedAt);
            })

            ->setDefault('created_at', function (Options $options) {
                return $this->faker->dateTimeBetween('-1 year', 'yesterday');
            })
            ->setAllowedTypes('created_at', ['null', 'string', \DateTimeInterface::class])
            ->setNormalizer('created_at', function (Options $options, $createdAt) {
                if (!is_string($createdAt)) {
                    return $createdAt;
                }

                return new \DateTime($createdAt);
            })

            ->setDefault('product', LazyOption::randomOne($this->productRepository))
            ->setAllowedTypes('product', ['null', 'string', ProductInterface::class])
            ->setNormalizer('product', LazyOption::findOneBy($this->productRepository, 'code'))

            ->setDefault('author', LazyOption::randomOne($this->customerRepository))
            ->setAllowedTypes('author', ['null', 'string', CustomerInterface::class])
            ->setNormalizer('author', LazyOption::findOneBy($this->customerRepository, 'email'));
    }
}
