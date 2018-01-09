<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Fixture\Factory;

use AppBundle\Entity\Article;
use AppBundle\Entity\GamePlay;
use AppBundle\Entity\Topic;
use AppBundle\Fixture\OptionsResolver\LazyOption;
use AppBundle\Formatter\StringInflector;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class TopicExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $topicFactory;

    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var RepositoryInterface
     */
    private $articleRepository;

    /**
     * @var RepositoryInterface
     */
    private $gamePlayRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $personFactory
     * @param RepositoryInterface $customerRepository
     * @param RepositoryInterface $articleRepository
     * @param RepositoryInterface $gamePlayRepository
     */
    public function __construct(
        FactoryInterface $personFactory,
        RepositoryInterface $customerRepository,
        RepositoryInterface $articleRepository,
        RepositoryInterface $gamePlayRepository
    )
    {
        $this->topicFactory = $personFactory;
        $this->customerRepository = $customerRepository;
        $this->articleRepository = $articleRepository;
        $this->gamePlayRepository = $gamePlayRepository;

        $this->faker = \Faker\Factory::create('fr_FR');
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var Topic $topic */
        $topic = $this->topicFactory->createNew();
        $topic->setCode($options['code']);
        $topic->setTitle($options['title']);
        $topic->setAuthor($options['author']);
        $topic->setCreatedAt($options['created_at']);
        $topic->setLastPostCreatedAt($topic->getCreatedAt());

        $mainPost = $topic->getMainPost();
        $mainPost->setBody($options['body']);
        $mainPost->setAuthor($topic->getAuthor());
        $mainPost->setCreatedAt($topic->getCreatedAt());

        if ($options['article']) {
            /** @var Article $article */
            $article = $options['article'];
            $article->setTopic($topic);
        } elseif ($options['game_play']) {
            /** @var GamePlay $gamePlay */
            $gamePlay = $options['game_play'];
            $gamePlay->setTopic($topic);
        }

        return $topic;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('title', function (Options $options) {
                return ucfirst($this->faker->words(3, true));
            })

            ->setDefault('code', function (Options $options) {
                return StringInflector::nameToCode($options['title']);
            })

            ->setDefault('body', function (Options $options) {
                return "<p>" . implode("</p><p>", $this->faker->paragraphs(5)) . '</p>';
            })

            ->setDefault('author', LazyOption::randomOne($this->customerRepository))
            ->setAllowedTypes('author', ['null', 'string', CustomerInterface::class])
            ->setNormalizer('author', LazyOption::findOneBy($this->customerRepository, 'email'))

            ->setDefault('created_at', function (Options $options) {
                return $this->faker->dateTimeBetween('-1 year', 'yesterday');
            } )
            ->setAllowedTypes('created_at', ['null', 'string', \DateTimeInterface::class])
            ->setNormalizer('created_at', function (Options $options, $createdAt) {
                if (!is_string($createdAt)) {
                    return $createdAt;
                }

                return new \DateTime($createdAt);
            })

            ->setDefault('article', LazyOption::randomOneOrNull($this->articleRepository, 50))
            ->setAllowedTypes('article', ['null', 'string', Article::class])
            ->setNormalizer('article', LazyOption::findOneBy($this->articleRepository, 'code'))

            ->setDefault('game_play', LazyOption::randomOneOrNull($this->gamePlayRepository, 50))
            ->setAllowedTypes('game_play', ['null', 'string', GamePlay::class])
            ->setNormalizer('game_play', LazyOption::findOneBy($this->gamePlayRepository, 'code'));
    }
}
