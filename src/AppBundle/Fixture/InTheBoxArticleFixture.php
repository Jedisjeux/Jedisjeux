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

class InTheBoxArticleFixture extends AbstractFixture
{
    /**
     * @var AbstractResourceFixture
     */
    private $taxonFixture;

    /**
     * @var AbstractResourceFixture
     */
    private $articleFixture;

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
     * @param AbstractResourceFixture $articleFixture
     */
    public function __construct($taxonFixture, AbstractResourceFixture $articleFixture)
    {
        $this->taxonFixture = $taxonFixture;
        $this->articleFixture = $articleFixture;

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
        return 'in_the_box_article';
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $options): void
    {
        $options = $this->optionsResolver->resolve($options);

        $this->taxonFixture->load(['custom' => [
            [
                'code' => 'articles',
                'name' => 'Categories',
                'children' => [
                    [
                        'code' => 'in-the-boxes',
                        'name' => 'In the boxes',
                    ],
                ],
            ],
        ]]);

        $articles = [];

        for ($i = 0; $i < $options['amount']; ++$i) {
            $articles[] = [
                'main_taxon' => 'news',
            ];
        }

        $this->articleFixture->load(['custom' => $articles]);
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
