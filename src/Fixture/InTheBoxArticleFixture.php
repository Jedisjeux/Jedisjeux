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

class InTheBoxArticleFixture extends AbstractFixture
{
    /**
     * @var TaxonFixture
     */
    private $taxonFixture;

    /**
     * @var ArticleFixture
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

    public function __construct(TaxonFixture $taxonFixture, ArticleFixture $articleFixture)
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
                        'translations' => [
                            'en_US' => [
                                'name' => 'In the boxes',
                            ],
                            'fr_FR' => [
                                'name' => 'C\'est dans la boîte',
                            ],
                        ],
                    ],
                ],
            ],
        ]]);

        $articles = [];

        for ($i = 0; $i < $options['amount']; ++$i) {
            $articles[] = [
                'main_taxon' => 'in-the-boxes',
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
