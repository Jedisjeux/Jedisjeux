<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture;

use App\Entity\Taxon;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameProductFixture extends AbstractFixture
{
    /**
     * @var TaxonFixture
     */
    private $taxonFixture;

    /**
     * @var ProductFixture
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
     * @param TaxonFixture   $taxonFixture
     * @param ProductFixture $productFixture
     */
    public function __construct(TaxonFixture $taxonFixture, ProductFixture $productFixture)
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
                'translations' => [
                    'en_US' => [
                        'name' => 'Medieval',
                    ],
                    'fr_FR' => [
                        'name' => 'Médiéval',
                    ],
                ],
            ],
        ];

        $mechanisms = [
            'mechanism1' => [
                'code' => 'mechanism1',
                'name' => 'Card Drafting',
            ],
            'mechanism2' => [
                'code' => 'mechanism2',
                'translations' => [
                    'en_US' => [
                        'name' => 'Tile placement',
                    ],
                    'fr_FR' => [
                        'name' => 'Placements de tuiles',
                    ],
                ],
            ],
        ];

        $targetAudiences = [
            Taxon::CODE_CHILD => [
                'code' => Taxon::CODE_CHILD,
                'icon_class' => 'fa fa-child',
                'color' => 'lblue',
                'translations' => [
                    'en_US' => [
                        'name' => 'Children',
                    ],
                    'fr_FR' => [
                        'name' => 'Enfants',
                    ],
                ],
            ],
            Taxon::CODE_BEGINNER => [
                'code' => Taxon::CODE_BEGINNER,
                'icon_class' => 'fa fa-user',
                'color' => 'green',
                'translations' => [
                    'en_US' => [
                        'name' => 'Beginners',
                    ],
                    'fr_FR' => [
                        'name' => 'Débutants',
                    ],
                ],
            ],
            Taxon::CODE_ADVANCED_USER => [
                'code' => Taxon::CODE_ADVANCED_USER,
                'icon_class' => 'fa fa-user-plus',
                'color' => 'purple',
                'translations' => [
                    'en_US' => [
                        'name' => 'Advanced users',
                    ],
                    'fr_FR' => [
                        'name' => 'Joueurs avancés',
                    ],
                ],
            ],
            Taxon::CODE_EXPERT => [
                'code' => Taxon::CODE_EXPERT,
                'icon_class' => 'fa fa-star',
                'color' => 'red',
                'translations' => [
                    'en_US' => [
                        'name' => 'Expert users',
                    ],
                    'fr_FR' => [
                        'name' => 'Experts',
                    ],
                ],
            ],
        ];

        $this->taxonFixture->load(['custom' => [
            [
                'code' => Taxon::CODE_THEME,
                'children' => $themes,
                'translations' => [
                    'en_US' => [
                        'name' => 'Themes',
                    ],
                    'fr_FR' => [
                        'name' => 'Thèmes',
                    ],
                ],
            ],
            [
                'code' => Taxon::CODE_MECHANISM,
                'children' => $mechanisms,
                'translations' => [
                    'en_US' => [
                        'name' => 'Mechanisms',
                    ],
                    'fr_FR' => [
                        'name' => 'Mécanismes',
                    ],
                ],
            ],
            [
                'code' => Taxon::CODE_TARGET_AUDIENCE,
                'children' => $targetAudiences,
                'translations' => [
                    'en_US' => [
                        'name' => 'Target audiences',
                    ],
                    'fr_FR' => [
                        'name' => 'Cibles',
                    ],
                ],
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
