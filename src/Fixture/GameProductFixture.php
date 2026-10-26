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

        $themes = $this->createTaxonChildren([
            'fantasy' => ['Fantasy', 'Fantasy'],
            'science-fiction' => ['Science-fiction', 'Science fiction'],
            'medieval' => ['Médiéval', 'Medieval'],
            'antiquity' => ['Antiquité', 'Antiquity'],
            'pirates' => ['Pirates', 'Pirates'],
            'nature' => ['Nature & animaux', 'Nature & animals'],
            'horror' => ['Horreur', 'Horror'],
            'investigation' => ['Enquête', 'Investigation'],
            'space' => ['Espace', 'Space'],
            'cooking' => ['Cuisine', 'Cooking'],
            'steampunk' => ['Steampunk', 'Steampunk'],
            'mythology' => ['Mythologie', 'Mythology'],
            'western' => ['Western', 'Western'],
            'trains' => ['Trains & transports', 'Trains & transport'],
            'farming' => ['Agriculture', 'Farming'],
            'war' => ['Guerre', 'War'],
        ]);

        $mechanisms = $this->createTaxonChildren([
            'worker-placement' => ['Placement d\'ouvriers', 'Worker placement'],
            'deck-building' => ['Deck-building', 'Deck building'],
            'drafting' => ['Draft', 'Card drafting'],
            'tile-placement' => ['Placement de tuiles', 'Tile placement'],
            'cooperative' => ['Coopératif', 'Cooperative'],
            'hand-management' => ['Gestion de main', 'Hand management'],
            'auction' => ['Enchères', 'Auction'],
            'deduction' => ['Déduction', 'Deduction'],
            'bluff' => ['Bluff', 'Bluffing'],
            'dice-rolling' => ['Lancer de dés', 'Dice rolling'],
            'roll-and-write' => ['Roll & write', 'Roll and write'],
            'dexterity' => ['Adresse', 'Dexterity'],
            'real-time' => ['Temps réel', 'Real time'],
            'set-collection' => ['Collection', 'Set collection'],
            'programming' => ['Programmation', 'Action programming'],
            'area-control' => ['Contrôle de territoire', 'Area control'],
            'hidden-roles' => ['Rôles cachés', 'Hidden roles'],
            'storytelling' => ['Narration', 'Storytelling'],
            'legacy' => ['Legacy', 'Legacy'],
            'pattern-building' => ['Construction de motifs', 'Pattern building'],
        ]);

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
     * @param array $names french and english names indexed by taxon code
     */
    private function createTaxonChildren(array $names): array
    {
        $children = [];

        foreach ($names as $code => [$frenchName, $englishName]) {
            $children[$code] = [
                'code' => $code,
                'translations' => [
                    'en_US' => [
                        'name' => $englishName,
                    ],
                    'fr_FR' => [
                        'name' => $frenchName,
                    ],
                ],
            ];
        }

        return $children;
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
