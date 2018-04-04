<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Fixture;

use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameProductFixture extends AbstractFixture
{
    /**
     * @var AbstractResourceFixture
     */
    private $taxonFixture;

    /**
     * @var AbstractResourceFixture
     */
    private $productFixture;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @param $taxonFixture
     * @param AbstractResourceFixture $productFixture
     */
    public function __construct($taxonFixture, AbstractResourceFixture $productFixture)
    {
        $this->taxonFixture = $taxonFixture;
        $this->productFixture = $productFixture;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver =
            (new OptionsResolver())
                ->setRequired('amount')
                ->setAllowedTypes('amount', 'int')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'game_product';
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $options): void
    {
        $options = $this->optionsResolver->resolve($options);

        $themes = [
            'theme1' => [
                'code' => 'theme1',
                'name' => 'Western',
            ],
            'theme2' => [
                'code' => 'theme2',
                'name' => 'Medieval',
            ]
        ];

        $mechanisms = [
            'mechanism1' => [
                'code' => 'mechanism1',
                'name' => 'Card Drafting',
            ],
            'mechanism2' => [
                'code' => 'mechanism2',
                'name' => 'Tile Placement',
            ]
        ];

        $this->taxonFixture->load(['custom' => [
            [
                'code' => 'themes',
                'name' => 'Themes',
                'children' => $themes,
            ],
            [
                'code' => 'mechanisms',
                'name' => 'Mechanisms',
                'children' => $mechanisms,
            ],
        ]]);

        $products = [];

        for ($i = 0; $i < $options['amount']; ++$i) {
            $products[] = [
                'mechanisms' => [$this->faker->randomKey($mechanisms)],
                'themes' => [$this->faker->randomKey($themes)],
            ];
        }

        $this->productFixture->load(['custom' => $products]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
            ->integerNode('amount')->isRequired()->min(0)->end()
        ;
    }
}