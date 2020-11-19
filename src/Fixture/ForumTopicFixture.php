<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture;

use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumTopicFixture extends AbstractFixture
{
    /**
     * @var AbstractResourceFixture
     */
    private $taxonFixture;

    /**
     * @var AbstractResourceFixture
     */
    private $topicFixture;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function __construct(
        AbstractResourceFixture $taxonFixture,
        AbstractResourceFixture $topicFixture
    ) {
        $this->taxonFixture = $taxonFixture;
        $this->topicFixture = $topicFixture;

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
