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

use Faker\Factory;
use Faker\Generator;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumTopicFixture extends AbstractFixture
{
    private OptionsResolver $optionsResolver;

    /**
     * @var Generator
     */
    private $faker;

    public function __construct(
        private AbstractResourceFixture $taxonFixture,
        private AbstractResourceFixture $topicFixture
    ) {
        $this->faker = Factory::create();
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
        return 'forum_topic';
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $options): void
    {
        $options = $this->optionsResolver->resolve($options);

        $categories = [
            'forum1' => [
                'code' => 'forum1',
                'name' => 'La Taverne du joueur',
                'public' => true,
            ],
            'forum2' => [
                'code' => 'forum2',
                'name' => 'Tatooïne : La Cantina',
                'public' => false,
            ],
        ];

        $this->taxonFixture->load(['custom' => [
            [
                'code' => 'forum',
                'name' => 'Forum',
                'children' => $categories,
            ],
        ]]);

        $topics = [];

        for ($i = 0; $i < $options['amount']; ++$i) {
            $topics[] = [
                'main_taxon' => $this->faker->randomKey($categories),
            ];
        }

        $this->topicFixture->load(['custom' => $topics]);
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
