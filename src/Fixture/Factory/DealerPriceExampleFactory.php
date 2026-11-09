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

use App\Entity\Dealer;
use App\Entity\DealerPrice;
use App\Fixture\OptionsResolver\LazyOption;
use Faker\Factory;
use Faker\Generator;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerPriceExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var Generator
     */
    private $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $dealerPriceFactory,
        private RepositoryInterface $productRepository,
        private RepositoryInterface $dealerRepository
    ) {
        $this->faker = Factory::create();
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

            ->setDefault('price', function (Options $options) {
                return $this->faker->randomNumber(4);
            })

            ->setDefault('url', function (Options $options): string {
                return ucfirst($this->faker->unique()->url);
            })

            ->setDefault('barcode', null)

            ->setDefault('status', function (Options $options) {
                return $this->faker->randomElement([
                    DealerPrice::STATUS_PRE_ORDER,
                    DealerPrice::STATUS_AVAILABLE,
                    DealerPrice::STATUS_OUT_OF_STOCK,
                ]);
            })

            ->setDefault('product', LazyOption::randomOneOrNull($this->productRepository, 50))
            ->setAllowedTypes('product', ['null', 'string', ProductInterface::class])
            ->setNormalizer('product', LazyOption::findOneBy($this->productRepository, 'code'))

            ->setDefault('dealer', LazyOption::randomOne($this->dealerRepository))
            ->setAllowedTypes('dealer', ['null', 'string', Dealer::class])
            ->setNormalizer('dealer', LazyOption::findOneBy($this->dealerRepository, 'name'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var DealerPrice $dealerPrice */
        $dealerPrice = $this->dealerPriceFactory->createNew();
        $dealerPrice->setName($options['name']);
        $dealerPrice->setPrice($options['price']);
        $dealerPrice->setUrl($options['url']);
        $dealerPrice->setBarcode($options['barcode']);
        $dealerPrice->setStatus($options['status']);
        $dealerPrice->setProduct($options['product']);
        $dealerPrice->setDealer($options['dealer']);

        return $dealerPrice;
    }
}
