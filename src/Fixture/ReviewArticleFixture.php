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

class ReviewArticleFixture extends AbstractFixture
{
    /**
     * @var TaxonFixture
     */
    private $taxonFixture;

    /***
     * @var ArticleReviewFixture
     */
    private $reviewFixture;

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
     * @param TaxonFixture         $taxonFixture
     * @param ArticleReviewFixture $reviewFixture
     * @param ArticleFixture       $articleFixture
     */
    public function __construct(
        TaxonFixture $taxonFixture,
        ArticleReviewFixture $reviewFixture,
        ArticleFixture $articleFixture
    ) {
        $this->taxonFixture = $taxonFixture;
        $this->reviewFixture = $reviewFixture;
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
        return 'review_article';
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
                        'code' => 'review-articles',
                        'name' => 'Reviews',
                    ],
                ],
            ],
        ]]);

        $articles = [];
        $articlesCodes = [];

        for ($i = 0; $i < $options['amount']; ++$i) {
            $articleCode = sprintf('review_article_%s', $i);
            $articlesCodes[] = $articleCode;

            $articles[] = [
                'code' => $articleCode,
                'main_taxon' => 'review-articles',
                'material_rating' => $this->faker->numberBetween(1, 5),
                'rules_rating' => $this->faker->numberBetween(1, 5),
                'lifetime_rating' => $this->faker->numberBetween(1, 5),
            ];
        }

        $this->articleFixture->load(['custom' => $articles]);

        foreach ($articlesCodes as $articleCode) {
            $this->reviewFixture->load(['custom' => [
                [
                    'article' => $articleCode,
                ],
            ]]);
        }
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
