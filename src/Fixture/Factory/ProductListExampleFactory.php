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

use App\Entity\ProductList;
use App\Entity\ProductListItem;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Customer\Model\CustomerInterface;
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
     * @var FactoryInterface
     */
    private $productListItemFactory;

    /**
     * @var RepositoryInterface
     */
    protected $customerRepository;

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
     * @param FactoryInterface $productListFactory
     * @param FactoryInterface $productListItemFactory
     * @param RepositoryInterface $customerRepository
     * @param RepositoryInterface $productRepository
     */
    public function __construct(
        FactoryInterface $productListFactory,
        FactoryInterface $productListItemFactory,
        RepositoryInterface $customerRepository,
        RepositoryInterface $productRepository
    )
    {
        $this->productListFactory = $productListFactory;
        $this->productListItemFactory = $productListItemFactory;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;

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
            ->setNormalizer('owner', LazyOption::findOneBy($this->customerRepository, 'email'))

            ->setDefault('products', LazyOption::randomOnes($this->productRepository, 10))
        ;
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

        $this->createItems($productList, $options['products']);

        return $productList;
    }

    public function createItems(ProductList $list, array $products)
    {
        foreach ($products as $product) {
            /** @var ProductListItem $item */
            $item = $this->productListItemFactory->createNew();
            $item->setProduct($product);
            $list->addItem($item);
        }
    }
}
