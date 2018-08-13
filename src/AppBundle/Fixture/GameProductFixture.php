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

use AppBundle\Entity\Taxon;
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

        $targetAudiences = [
            Taxon::CODE_CHILD => [
                'code' => Taxon::CODE_CHILD,
                'name' => 'Child',
                'icon_class' => 'fa fa-child',
                'color' => 'lblue',
            ],
            Taxon::CODE_BEGINNER => [
                'code' => Taxon::CODE_BEGINNER,
                'name' => 'Beginner',
                'icon_class' => 'fa fa-user',
                'color' => 'green',
            ],
            Taxon::CODE_ADVANCED_USER => [
                'code' => Taxon::CODE_ADVANCED_USER,
                'name' => 'Advanced user',
                'icon_class' => 'fa fa-user-plus',
                'color' => 'purple',
            ],
            Taxon::CODE_EXPERT => [
                'code' => Taxon::CODE_EXPERT,
                'name' => 'Expert',
                'icon_class' => 'fa fa-star',
                'color' => 'red',
            ],
        ];

        $this->taxonFixture->load(['custom' => [
            [
                'code' => Taxon::CODE_THEME,
                'name' => 'Themes',
                'children' => $themes,
            ],
            [
                'code' => Taxon::CODE_MECHANISM,
                'name' => 'Mechanisms',
                'children' => $mechanisms,
            ],
            [
                'code' => Taxon::CODE_TARGET_AUDIENCE,
                'name' => 'Target audiences',
                'children' => $targetAudiences,
            ],
        ]]);

        $products = [];

        for ($i = 0; $i < $options['amount']; ++$i) {
            $products[] = [
                'mechanisms' => [$this->faker->randomKey($mechanisms)],
                'themes' => [$this->faker->randomKey($themes)],
                'main_taxon' => $this->faker->randomKey($targetAudiences),
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