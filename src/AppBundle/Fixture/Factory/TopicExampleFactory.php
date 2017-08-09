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

use AppBundle\Entity\Person;
use AppBundle\Entity\PersonImage;
use AppBundle\Entity\ProductVariantImage;
use AppBundle\Entity\Topic;
use AppBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Corentin Nicole <corentin@mobizel.com>
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
     */
    public function __construct(
        FactoryInterface $personFactory,
        RepositoryInterface $customerRepository
    )
    {
        $this->topicFactory = $personFactory;
        $this->customerRepository = $customerRepository;

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
        $topic->setTitle($options['title']);
        $topic->setAuthor($options['author']);
        $topic->setCreatedAt($options['created_at']);;
        $topic->getMainPost()
            ->setBody($options['body'])
            ->setAuthor($topic->getAuthor())
            ->setCreatedAt($topic->getCreatedAt());
        $topic->setLastPostCreatedAt($topic->getCreatedAt());
        $topic->setLastPost($topic->getMainPost());

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
            ->setDefault('body', function (Options $options) {
                return "<p>" . implode("</p><p>", $this->faker->paragraphs(5)) . '</p>';
            })
            ->setDefault('author', LazyOption::randomOne($this->customerRepository))
            ->setAllowedTypes('author', ['null', 'string', CustomerInterface::class])
            ->setNormalizer('author', LazyOption::findOneBy($this->customerRepository, 'email'))
            ->setDefault('created_at', function (Options $options) {
                return $this->faker->dateTimeBetween('2 months ago', 'today');
            });
    }
}
