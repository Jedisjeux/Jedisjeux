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

use App\Entity\FestivalList;
use App\Formatter\StringInflector;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class FestivalListExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $festivalListFactory;

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
     */
    public function __construct(FactoryInterface $festivalListFactory)
    {
        $this->festivalListFactory = $festivalListFactory;

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
            ->setDefault('name', function (Options $options) {
                return ucfirst($this->faker->unique()->words(3, true));
            })

            ->setDefault('code', function (Options $options) {
                return StringInflector::nameToCode($options['name']);
            })

            ->setDefault('description', function (Options $options) {
                return '<p>'.implode('</p><p>', $this->faker->paragraphs(5)).'</p>';
            })

            ->setDefault('start_at', function (Options $options) {
                return $this->faker->dateTimeBetween('2 months ago', 'yesterday');
            })
            ->setNormalizer('start_at', function (Options $options, $createdAt) {
                if (!is_string($createdAt)) {
                    return $createdAt;
                }

                return new \DateTime($createdAt);
            })

            ->setDefault('end_at', function (Options $options) {
                return $this->faker->dateTimeBetween('tomorrow', '2 months');
            })
            ->setNormalizer('end_at', function (Options $options, $endAt) {
                if (!is_string($endAt)) {
                    return $endAt;
                }

                return new \DateTime($endAt);
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var FestivalList $festivalList */
        $festivalList = $this->festivalListFactory->createNew();
        $festivalList->setName($options['name']);
        $festivalList->setDescription($options['description']);
        $festivalList->setStartAt($options['start_at']);
        $festivalList->setEndAt($options['end_at']);

        return $festivalList;
    }
}
