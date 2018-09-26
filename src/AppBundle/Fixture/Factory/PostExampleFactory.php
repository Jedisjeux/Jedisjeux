<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Fixture\Factory;

use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use AppBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $postFactory;

    /**
     * @var RepositoryInterface
     */
    private $topicRepository;

    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * PostExampleFactory constructor.
     *
     * @param FactoryInterface $postFactory
     * @param RepositoryInterface $topicRepository
     * @param RepositoryInterface $customerRepository
     */
    public function __construct(
        FactoryInterface $postFactory,
        RepositoryInterface $topicRepository,
        RepositoryInterface $customerRepository
    )
    {
        $this->postFactory = $postFactory;
        $this->topicRepository = $topicRepository;
        $this->customerRepository = $customerRepository;

        $this->faker = \Faker\Factory::create('fr_FR');
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('body', function (Options $options) {
                return "<p>" . implode("</p><p>", $this->faker->paragraphs(5)) . '</p>';
            })

            ->setDefault('topic', LazyOption::randomOne($this->topicRepository))
            ->setAllowedTypes('topic', ['null', 'string', Topic::class])
            ->setNormalizer('topic', LazyOption::findOneBy($this->topicRepository, 'code'))

            ->setDefault('game_play', null)

            ->setDefault('article', null)

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
            });
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var Post $post */
        $post = $this->postFactory->createNew();
        $post->setBody($options['body']);
        $post->setGamePlay($options['game_play']);
        $post->setArticle($options['article']);
        $post->setTopic($options['topic']);
        $post->setAuthor($options['author']);
        $post->setCreatedAt($options['created_at']);

        /** @var Topic $topic */
        $topic = $post->getTopic();
        $topic->setLastPostCreatedAt($topic->getLastPost()->getCreatedAt());

        return $post;
    }
}
