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

use AppBundle\Entity\ProductBox;
use AppBundle\Entity\ProductBoxImage;
use AppBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductBoxExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $postFactory;

    /**
     * @var FactoryInterface
     */
    private $productBoxImageFactory;

    /**
     * @var RepositoryInterface
     */
    private $productVariantRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * PostExampleFactory constructor.
     *
     * @param FactoryInterface $postFactory
     * @param FactoryInterface $productBoxImageFactory
     * @param RepositoryInterface $productVariantRepository
     */
    public function __construct(
        FactoryInterface $postFactory,
        FactoryInterface $productBoxImageFactory,
        RepositoryInterface $productVariantRepository
    ) {
        $this->postFactory = $postFactory;
        $this->productVariantRepository = $productVariantRepository;
        $this->productBoxImageFactory = $productBoxImageFactory;

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
            ->setDefault('height', function (Options $options) {
                return $this->faker->numberBetween(20, 100);
            })

            ->setDefault('product_variant', LazyOption::randomOne($this->productVariantRepository))
            ->setAllowedTypes('product_variant', ['null', 'string', ProductVariantInterface::class])
            ->setNormalizer('product_variant', LazyOption::findOneBy($this->productVariantRepository, 'code'))

            ->setDefault('image', function (Options $options) {
                return $this->faker->image();
            });
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ProductBox $productBox */
        $productBox = $this->postFactory->createNew();
        $productBox->setHeight($options['height']);
        $productBox->setProductVariant($options['product_variant']);

        $this->createImage($productBox, $options);

        return $productBox;
    }

    /**
     * @param ProductBox $productBox
     * @param array $options
     */
    private function createImage(ProductBox $productBox, array $options)
    {
        $imagePath = $options['image'];
        /** @var ProductBoxImage $image */
        $image = $this->productBoxImageFactory->createNew();
        $image->setPath(basename($imagePath));
        file_put_contents($image->getAbsolutePath(), file_get_contents($imagePath));

        $productBox->setImage($image);

    }
}