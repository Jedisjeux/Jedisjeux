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
use Faker\Factory;
use Faker\Generator;
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
     * @var Generator
     */
    private $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $productListFactory,
        private FactoryInterface $productListItemFactory,
        private RepositoryInterface $customerRepository,
        private RepositoryInterface $productRepository
    ) {
        $this->faker = Factory::create('fr_FR');
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('name', function (Options $options): string {
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

    public function createItems(ProductList $list, array $products): void
    {
        foreach ($products as $product) {
            /** @var ProductListItem $item */
            $item = $this->productListItemFactory->createNew();
            $item->setProduct($product);
            $list->addItem($item);
        }
    }
}
