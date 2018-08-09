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
use AppBundle\Entity\ProductVariant;
use AppBundle\Entity\ProductVariantImage;
use AppBundle\Fixture\OptionsResolver\LazyOption;
use AppBundle\Formatter\StringInflector;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var ProductFactoryInterface
     */
    private $productFactory;

    /**
     * @var FactoryInterface
     */
    private $productVariantImageFactory;

    /**
     * @var RepositoryInterface
     */
    protected $personRepository;

    /**
     * @var RepositoryInterface
     */
    protected $taxonRepository;

    /**
     * @var SlugGeneratorInterface
     */
    private $slugGenerator;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param ProductFactoryInterface $productFactory
     * @param FactoryInterface $productVariantImageFactory
     * @param RepositoryInterface $personRepository
     * @param RepositoryInterface $taxonRepository
     * @param SlugGeneratorInterface $slugGenerator
     */
    public function __construct(
        ProductFactoryInterface $productFactory,
        FactoryInterface $productVariantImageFactory,
        RepositoryInterface $personRepository,
        RepositoryInterface $taxonRepository,
        SlugGeneratorInterface $slugGenerator
    )
    {
        $this->productFactory = $productFactory;
        $this->productVariantImageFactory = $productVariantImageFactory;
        $this->personRepository = $personRepository;
        $this->taxonRepository = $taxonRepository;
        $this->slugGenerator = $slugGenerator;

        $this->faker = \Faker\Factory::create('fr_FR');
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var Product $product */
        $product = $this->productFactory->createWithVariant();
        $product->setCode($options['code']);
        $product->setName($options['name']);
        $product->setSlug($this->slugGenerator->generate($options['name']));
        $product->setMinPlayerCount($options['min_player_count']);
        $product->setMaxPlayerCount($options['max_player_count']);
        $product->setMinDuration($options['min_duration']);
        $product->setMaxDuration($options['max_duration']);
        $product->setMinAge($options['min_age']);
        $product->setShortDescription($options['short_description']);
        $product->setDescription($options['description']);
        $product->setBoxContent($options['material']);
        $product->setStatus($options['status']);
        $product->setReleasedAt($options['released_at']);
        $product->setCreatedAt($options['created_at']);

        $firstVariant = $product->getFirstVariant();
        $firstVariant->setCode($product->getCode());
        $firstVariant->setReleasedAtPrecision($options['released_at_precision']);

        foreach ($options['designers'] as $designer) {
            $firstVariant->addDesigner($designer);
        }

        foreach ($options['artists'] as $artist) {
            $firstVariant->addArtist($artist);
        }

        foreach ($options['publishers'] as $publisher) {
            $firstVariant->addPublisher($publisher);
        }

        foreach ($options['mechanisms'] as $mechanism) {
            $product->addMechanism($mechanism);
        }

        foreach ($options['themes'] as $theme) {
            $product->addTheme($theme);
        }

        $this->createImages($product, $options);

        return $product;
    }

    /**
     * @param Product $product
     * @param array $options
     */
    private function createImages(Product $product, array $options)
    {
        $first = true;

        foreach ($options['images'] as $imagePath) {
            /** @var ProductVariantImage $image */
            $image = $this->productVariantImageFactory->createNew();
            $image->setPath(basename($imagePath));

            if ($first) {
                $image->setMain(true);
            }

            $first = false;

            file_put_contents($image->getAbsolutePath(), file_get_contents($imagePath));

            $product->getFirstVariant()->addImage($image);
        }
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
                return StringInflector::nameToCode($options['name']);
            })

            ->setDefault('status', function (Options $options) {
                return $this->faker->randomElement([
                    Product::STATUS_NEW,
                    Product::PENDING_TRANSLATION,
                    Product::PENDING_REVIEW,
                    Product::PENDING_PUBLICATION,
                    Product::PUBLISHED,
                ]);
            })

            ->setDefault('min_player_count', function (Options $options) {
                return $this->faker->numberBetween(2, 3);
            })

            ->setDefault('max_player_count', function (Options $options) {
                return $this->faker->numberBetween(4, 8);
            })

            ->setDefault('min_age', function (Options $options) {
                return $this->faker->numberBetween(3, 12);
            })

            ->setDefault('min_duration', function (Options $options) {
                return $this->faker->randomElement([30, 45, 60, 90, 180]);
            })

            ->setDefault('max_duration', function (Options $options) {
                return $this->faker->randomElement([30, 45, 60, 90, 180]);
            })

            ->setDefault('short_description', function (Options $options) {
                return "<p>" . implode("</p><p>", $this->faker->paragraphs(2)) . '</p>';
            })

            ->setDefault('description', function (Options $options) {
                return "<p>" . implode("</p><p>", $this->faker->paragraphs(5)) . '</p>';
            })

            ->setDefault('material', function (Options $options) {

                $itemCount = $this->faker->numberBetween(5, 10);

                $materialList = [];

                for($i = 0 ; $i < $itemCount ; $i++) {
                    $materialList[] = $this->faker->words(3, true);
                }

                return implode("\n", $materialList);
            })
            ->setDefault('images', function (Options $options) {

                $image = $this->faker->image();

                if (!$image) {
                    return [];
                }

                return [$image];
            })

            ->setDefault('designers', LazyOption::randomOnes($this->personRepository, 2))
            ->setAllowedTypes('designers', 'array')
            ->setNormalizer('designers', LazyOption::findBy($this->personRepository, 'slug'))

            ->setDefault('artists', LazyOption::randomOnes($this->personRepository, 2))
            ->setAllowedTypes('artists', 'array')
            ->setNormalizer('artists', LazyOption::findBy($this->personRepository, 'slug'))

            ->setDefault('publishers', LazyOption::randomOnes($this->personRepository, 2))
            ->setAllowedTypes('publishers', 'array')
            ->setNormalizer('publishers', LazyOption::findBy($this->personRepository, 'slug'))

            ->setDefault('mechanisms', LazyOption::randomOnes($this->taxonRepository, 2))
            ->setAllowedTypes('mechanisms', 'array')
            ->setNormalizer('mechanisms', LazyOption::findBy($this->taxonRepository, 'code'))

            ->setDefault('themes', LazyOption::randomOnes($this->taxonRepository, 2))
            ->setAllowedTypes('themes', 'array')
            ->setNormalizer('themes', LazyOption::findBy($this->taxonRepository, 'code'))

            ->setDefault('released_at', function (Options $options) {
                return $this->faker->dateTimeBetween('-1 year', 'yesterday');
            })
            ->setAllowedTypes('released_at', ['null', 'string', \DateTimeInterface::class])
            ->setNormalizer('released_at', function (Options $options, $releasedAt) {
                if (!is_string($releasedAt)) {
                    return $releasedAt;
                }

                return new \DateTime($releasedAt);
            })

            ->setDefault('released_at_precision', function (Options $options) {
                return $this->faker->randomElement([
                    ProductVariant::RELEASED_AT_PRECISION_ON_DAY,
                    ProductVariant::RELEASED_AT_PRECISION_ON_MONTH,
                    ProductVariant::RELEASED_AT_PRECISION_ON_YEAR,
                ]);
            })

            ->setDefault('created_at', function (Options $options) {
                return $this->faker->dateTimeBetween('-1 year', 'yesterday');
            } )
            ->setAllowedTypes('created_at', ['null', 'string', \DateTimeInterface::class])
            ->setNormalizer('created_at', function (Options $options, $createdAt) {
                if (!is_string($createdAt)) {
                    return $createdAt;
                }

                return new \DateTime($createdAt);
            })
        ;
    }
}
