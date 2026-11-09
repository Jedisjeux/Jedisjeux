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
use Faker\Factory;
use Faker\Generator;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Review\Factory\ReviewFactoryInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
class ProductReviewExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var Generator
     */
    private $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private ReviewFactoryInterface $productReviewFactory,
        private RepositoryInterface $productRepository,
        private RepositoryInterface $customerRepository
    ) {
        $this->faker = Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ReviewInterface $productReview */
        $productReview = $this->productReviewFactory->createForSubjectWithReviewer(
            $options['product'],
            $options['author']
        );
        $productReview->setTitle($options['title']);
        $productReview->setComment($options['comment']);
        $productReview->setRating($options['rating']);
        $options['product']->addReview($productReview);

        return $productReview;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('title', function (Options $options) {
                return $this->faker->words(3, true);
            })
            ->setDefault('rating', function (Options $options) {
                return $this->faker->numberBetween(1, 10);
            })
            ->setDefault('comment', function (Options $options) {
                return $this->faker->sentences(3, true);
            })
            ->setDefault('author', LazyOption::randomOne($this->customerRepository))
            ->setNormalizer('author', LazyOption::findOneBy($this->customerRepository, 'email'))
            ->setDefault('product', LazyOption::randomOne($this->productRepository))
            ->setNormalizer('product', LazyOption::findOneBy($this->productRepository, 'code'))
        ;
    }
}
