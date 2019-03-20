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

use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsArticleFixture extends AbstractFixture
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

    /**
     * @param TaxonFixture   $taxonFixture
     * @param ArticleFixture $articleFixture
     */
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
        return 'news_article';
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
                        'code' => 'news',
                        'icon_class' => 'fa fa-newspaper-o bg-purple',
                        'color' => 'purple',
                        'translations' => [
                            'en_US' => [
                                'name' => 'News',
                            ],
                            'fr_FR' => [
                                'name' => 'Actualités',
                            ],
                        ],
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
