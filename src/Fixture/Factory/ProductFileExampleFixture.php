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

use App\Entity\ProductFile;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFileExampleFixture extends AbstractExampleFactory
{
    /**
     * @var FactoryInterface
     */
    private $productFileFactory;

    /**
     * @var RepositoryInterface
     */
    private $productRepository;

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
     * @param FactoryInterface    $productFileFactory
     * @param RepositoryInterface $productRepository
     * @param RepositoryInterface $customerRepository
     */
    public function __construct(
        FactoryInterface $productFileFactory,
        RepositoryInterface $productRepository,
        RepositoryInterface $customerRepository
    ) {
        $this->productFileFactory = $productFileFactory;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;

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
                return sprintf('%s.%s', $this->faker->word, $this->faker->fileExtension);
            })

            ->setDefault('status', function (Options $options) {
                return $this->faker->randomElement([
                    ProductFile::STATUS_NEW,
                    ProductFile::STATUS_ACCEPTED,
                    ProductFile::STATUS_REJECTED,
                ]);
            })

            ->setDefault('product', LazyOption::randomOne($this->productRepository))
            ->setAllowedTypes('product', ['null', 'string', ProductInterface::class])
            ->setNormalizer('product', LazyOption::findOneBy($this->productRepository, 'code'))

            ->setDefault('author', LazyOption::randomOne($this->customerRepository))
            ->setAllowedTypes('author', ['null', 'string', CustomerInterface::class])
            ->setNormalizer('author', LazyOption::findOneBy($this->customerRepository, 'email'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ProductFile $productFile */
        $productFile = $this->productFileFactory->createNew();
        $productFile->setTitle($options['title']);
        $productFile->setPath($options['path']);
        $productFile->setProduct($options['product']);
        $productFile->setAuthor($options['author']);
        $productFile->setStatus($options['status']);

        return $productFile;
    }
}
