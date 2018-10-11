<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\Factory;

use App\Entity\Redirection;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class RedirectionExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $redirectionFactory;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * RedirectionExampleFactory constructor.
     *
     * @param FactoryInterface $redirectionFactory
     */
    public function __construct(FactoryInterface $redirectionFactory)
    {
        $this->redirectionFactory = $redirectionFactory;

        $this->faker = \Faker\Factory::create('fr_FR');
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('source', function (Options $options) {
                return "/" . $this->faker->unique()->slug();
            })

            ->setDefault('destination', function (Options $options) {
                return "/" . $this->faker->slug();
            })

            ->setDefault('permanent', function (Options $options) {
                return $this->faker->boolean(90);
            })
            ->setAllowedTypes('permanent', 'bool')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var Redirection $redirection */
        $redirection = $this->redirectionFactory->createNew();
        $redirection->setSource($options['source']);
        $redirection->setDestination($options['destination']);
        $redirection->setPermanent($options['permanent']);

        return $redirection;
    }
}
