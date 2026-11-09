<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Fixture\Factory;

use App\ProductAssociationTypes;
use Faker\Factory;
use Faker\Generator;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProductAssociationTypeExampleFactory extends AbstractExampleFactory
{
    /** @var Generator */
    private $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $productAssociationTypeFactory
    ) {
        $this->faker = Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = []): ProductAssociationTypeInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ProductAssociationTypeInterface $productAssociationType */
        $productAssociationType = $this->productAssociationTypeFactory->createNew();
        $productAssociationType->setCode($options['code']);
        $productAssociationType->setName($options['name']);

        return $productAssociationType;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('code', function (Options $options) {
                return $this->faker->randomElement([ProductAssociationTypes::COLLECTION, ProductAssociationTypes::EXPANSION]);
            })
            ->setDefault('name', function (Options $options): string {
                return ucfirst($options['code']);
            })
        ;
    }
}
