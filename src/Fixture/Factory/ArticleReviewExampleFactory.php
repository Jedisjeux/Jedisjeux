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

use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Review\Factory\ReviewFactoryInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
class ArticleReviewExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var ReviewFactoryInterface
     */
    private $articleReviewFactory;

    /**
     * @var RepositoryInterface
     */
    private $articleRepository;

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
     * @param ReviewFactoryInterface $articleReviewFactory
     * @param RepositoryInterface $articleRepository
     * @param RepositoryInterface $customerRepository
     */
    public function __construct(
        ReviewFactoryInterface $articleReviewFactory,
        RepositoryInterface $articleRepository,
        RepositoryInterface $customerRepository
    ) {
        $this->articleReviewFactory = $articleReviewFactory;
        $this->articleRepository = $articleRepository;
        $this->customerRepository = $customerRepository;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ReviewInterface $articleReview */
        $articleReview = $this->articleReviewFactory->createForSubjectWithReviewer(
            $options['article'],
            $options['author']
        );
        $articleReview->setComment($options['comment']);
        $articleReview->setRating($options['rating']);
        $options['article']->addReview($articleReview);

        return $articleReview;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('rating', function (Options $options) {
                return $this->faker->numberBetween(1, 5);
            })

            ->setDefault('comment', function (Options $options) {
                return $this->faker->sentences(3, true);
            })

            ->setDefault('author', LazyOption::randomOne($this->customerRepository))
            ->setNormalizer('author', LazyOption::findOneBy($this->customerRepository, 'email'))

            ->setDefault('article', LazyOption::randomOne($this->articleRepository))
            ->setNormalizer('article', LazyOption::findOneBy($this->articleRepository, 'code'))
        ;
    }
}
