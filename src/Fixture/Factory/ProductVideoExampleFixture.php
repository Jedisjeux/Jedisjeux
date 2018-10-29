<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\Factory;

use App\Entity\ProductVideo;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVideoExampleFixture extends AbstractExampleFactory
{
    /**
     * @var FactoryInterface
     */
    private $productVideoFactory;

    /**
     * @var RepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $productVideoFactory
     * @param RepositoryInterface $productRepository
     */
    public function __construct(FactoryInterface $productVideoFactory, RepositoryInterface $productRepository)
    {
        $this->productVideoFactory = $productVideoFactory;
        $this->productRepository = $productRepository;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('title', function (Options $options) {
                return ucfirst($this->faker->unique()->words(3, true));
            })

            ->setDefault('path', function (Options $options) {
                return $this->faker->url;
            })

            ->setDefault('image', LazyOption::randomOneImageOrNull(
                __DIR__.'/../../../tests/Resources/fixtures/videos', 80
            ))

            ->setDefault('product', LazyOption::randomOne($this->productRepository))
            ->setAllowedTypes('product', ['null', 'string', ProductInterface::class])
            ->setNormalizer('product', LazyOption::findOneBy($this->productRepository, 'code'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ProductVideo $productVideo */
        $productVideo = $this->productVideoFactory->createNew();
        $productVideo->setTitle($options['title']);
        $productVideo->setPath($options['path']);
        $productVideo->setProduct($options['product']);

        return $productVideo;
    }
}
