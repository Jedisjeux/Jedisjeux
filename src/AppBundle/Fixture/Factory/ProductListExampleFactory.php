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

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductList;
use AppBundle\Entity\ProductVariant;
use AppBundle\Entity\ProductVariantImage;
use AppBundle\Fixture\OptionsResolver\LazyOption;
use AppBundle\Formatter\StringInflector;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductListExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $productListFactory;

    /**
     * @var RepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $productListFactory
     * @param RepositoryInterface $customerRepository
     */
    public function __construct(
        FactoryInterface $productListFactory,
        RepositoryInterface $customerRepository
    )
    {
        $this->productListFactory = $productListFactory;
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
            ->setDefault('name', function (Options $options) {
                return ucfirst($this->faker->words(3, true));
            })

            ->setDefault('code', function (Options $options) {
                return $this->faker->randomElement([
                    ProductList::CODE_GAME_LIBRARY,
                    ProductList::CODE_WISHES,
                    ProductList::CODE_SEE_LATER,
                ]);
            })

            ->setDefault('owner', LazyOption::randomOne($this->customerRepository))
            ->setAllowedTypes('owner', ['null', 'string', CustomerInterface::class])
            ->setNormalizer('owner', LazyOption::findOneBy($this->customerRepository, 'email'));
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ProductList $productList */
        $productList = $this->productListFactory->createNew();
        $productList->setName($options['name']);
        $productList->setCode($options['code']);
        $productList->setOwner($options['owner']);

        return $productList;
    }
}
