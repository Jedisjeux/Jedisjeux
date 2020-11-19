<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Fixture\Factory;

use App\ProductAssociationTypes;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProductAssociationTypeExampleFactory extends AbstractExampleFactory
{
    /** @var FactoryInterface */
    private $productAssociationTypeFactory;

    /** @var \Faker\Generator */
    private $faker;

    /** @var OptionsResolver */
    private $optionsResolver;

    public function __construct(
        FactoryInterface $productAssociationTypeFactory
    ) {
        $this->productAssociationTypeFactory = $productAssociationTypeFactory;

        $this->faker = \Faker\Factory::create();
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
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('code', function (Options $options) {
                return $this->faker->randomElement([ProductAssociationTypes::COLLECTION, ProductAssociationTypes::EXPANSION]);
            })
            ->setDefault('name', function (Options $options) {
                return ucfirst($options['code']);
            })
        ;
    }
}
