<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\Factory;

use App\Entity\Article;
use App\Entity\GamePlay;
use App\Factory\TopicFactory;
use App\Fixture\OptionsResolver\LazyOption;
use App\Formatter\StringInflector;
use Faker\Factory;
use Faker\Generator;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class TopicExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var Generator
     */
    private $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private TopicFactory $topicFactory,
        private RepositoryInterface $customerRepository,
        private RepositoryInterface $articleRepository,
        private RepositoryInterface $gamePlayRepository,
        private RepositoryInterface $taxonRepository
    ) {
        $this->faker = Factory::create('fr_FR');
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /* @var Topic $topic */
        if ($options['article']) {
            $topic = $this->topicFactory->createForArticle($options['article']);
        } elseif ($options['game_play']) {
            $topic = $this->topicFactory->createForGamePlay($options['game_play']);
        } else {
            $topic = $this->topicFactory->createNew();
        }

        $topic->setCode($options['code']);
        $topic->setTitle($options['title']);
        $topic->setAuthor($options['author']);
        $topic->setMainTaxon($options['main_taxon']);
        $topic->setCreatedAt($options['created_at']);
        $topic->setLastPostCreatedAt($topic->getCreatedAt());

        if (null !== $mainPost = $topic->getMainPost()) {
            $mainPost->setBody($options['body']);
            $mainPost->setAuthor($topic->getAuthor());
            $mainPost->setCreatedAt($topic->getCreatedAt());
        }

        return $topic;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('title', function (Options $options): string {
                return ucfirst($this->faker->words(3, true));
            })

            ->setDefault('code', function (Options $options): string {
                return StringInflector::nameToCode($options['title']);
            })

            ->setDefault('body', function (Options $options): string {
                return '<p>'.implode('</p><p>', $this->faker->paragraphs(5)).'</p>';
            })

            ->setDefault('author', LazyOption::randomOne($this->customerRepository))
            ->setAllowedTypes('author', ['null', 'string', CustomerInterface::class])
            ->setNormalizer('author', LazyOption::findOneBy($this->customerRepository, 'email'))

            ->setDefault('created_at', function (Options $options) {
                return $this->faker->dateTimeBetween('-1 year', 'yesterday');
            })
            ->setAllowedTypes('created_at', ['null', 'string', \DateTimeInterface::class])
            ->setNormalizer('created_at', function (Options $options, $createdAt) {
                if (!is_string($createdAt)) {
                    return $createdAt;
                }

                return new \DateTime($createdAt);
            })

            ->setDefault('main_taxon', LazyOption::randomOneOrNull($this->taxonRepository, 50))
            ->setAllowedTypes('main_taxon', ['null', 'string', TaxonInterface::class])
            ->setNormalizer('main_taxon', LazyOption::findOneBy($this->taxonRepository, 'code'))

            ->setDefault('article', LazyOption::randomOneOrNull($this->articleRepository, 50))
            ->setAllowedTypes('article', ['null', 'string', Article::class])
            ->setNormalizer('article', LazyOption::findOneBy($this->articleRepository, 'code'))

            ->setDefault('game_play', LazyOption::randomOneOrNull($this->gamePlayRepository, 50))
            ->setAllowedTypes('game_play', ['null', 'string', GamePlay::class])
            ->setNormalizer('game_play', LazyOption::findOneBy($this->gamePlayRepository, 'code'));
    }
}
