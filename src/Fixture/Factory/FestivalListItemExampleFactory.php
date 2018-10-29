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

use App\Entity\FestivalList;
use App\Entity\FestivalListItem;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class FestivalListItemExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $festivalListItemFactory;

    /**
     * @var RepositoryInterface
     */
    private $productRepository;

    /**
     * @var RepositoryInterface
     */
    private $festivalListRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface    $festivalListItemFactory
     * @param RepositoryInterface $productRepository
     * @param RepositoryInterface $festivalListRepository
     */
    public function __construct(
        FactoryInterface $festivalListItemFactory,
        RepositoryInterface $productRepository,
        RepositoryInterface $festivalListRepository
    ) {
        $this->festivalListItemFactory = $festivalListItemFactory;
        $this->productRepository = $productRepository;
        $this->festivalListRepository = $festivalListRepository;

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
            ->setDefault('product', LazyOption::randomOne($this->productRepository))
            ->setAllowedTypes('product', ['null', 'string', ProductInterface::class])
            ->setNormalizer('product', LazyOption::findOneBy($this->productRepository, 'code'))

            ->setDefault('list', LazyOption::randomOne($this->festivalListRepository))
            ->setAllowedTypes('list', ['null', 'string', FestivalList::class])
            ->setNormalizer('list', LazyOption::findOneBy($this->festivalListRepository, 'code'))

            ->setDefault('created_at', function (Options $options) {
                return $this->faker->dateTimeBetween('2 months ago', 'yesterday');
            })
            ->setNormalizer('created_at', function (Options $options, $createdAt) {
                if (!is_string($createdAt)) {
                    return $createdAt;
                }

                return new \DateTime($createdAt);
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var FestivalListItem $festivalListItem */
        $festivalListItem = $this->festivalListItemFactory->createNew();
        $festivalListItem->setProduct($options['product']);
        $festivalListItem->setList($options['list']);
        $festivalListItem->setCreatedAt($options['created_at']);

        return $festivalListItem;
    }
}
