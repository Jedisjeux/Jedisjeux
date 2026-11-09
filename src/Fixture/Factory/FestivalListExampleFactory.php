<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\Factory;

use App\Entity\FestivalList;
use App\Formatter\StringInflector;
use Faker\Factory;
use Faker\Generator;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class FestivalListExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var Generator
     */
    private $faker;

    private OptionsResolver $optionsResolver;

    /**
     * FestivalListExampleFactory constructor.
     */
    public function __construct(private FactoryInterface $festivalListFactory)
    {
        $this->faker = Factory::create('fr_FR');
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('name', function (Options $options): string {
                return ucfirst($this->faker->unique()->words(3, true));
            })

            ->setDefault('code', function (Options $options): string {
                return StringInflector::nameToCode($options['name']);
            })

            ->setDefault('description', function (Options $options): string {
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
