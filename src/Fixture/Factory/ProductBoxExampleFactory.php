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

use App\Entity\ProductBox;
use App\Entity\ProductBoxImage;
use App\Entity\ProductVariantInterface;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductBoxExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $productBoxFactory;

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
     * @param FactoryInterface    $productBoxFactory
     * @param FactoryInterface    $productBoxImageFactory
     * @param RepositoryInterface $productVariantRepository
     */
    public function __construct(
        FactoryInterface $productBoxFactory,
        FactoryInterface $productBoxImageFactory,
        RepositoryInterface $productVariantRepository
    ) {
        $this->productBoxFactory = $productBoxFactory;
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
            ->setDefault('product_variant', LazyOption::randomOne($this->productVariantRepository))
            ->setAllowedTypes('product_variant', ['null', 'string', ProductVariantInterface::class])
            ->setNormalizer('product_variant', LazyOption::findOneBy($this->productVariantRepository, 'code'))

            ->setDefault('image', LazyOption::randomOneImage(
                __DIR__.'/../../../tests/Resources/fixtures/boxes'
            ))

            ->setDefault('height', function (Options $options) {
                $imageSize = getimagesize(realpath($options['image']));
                $height = $imageSize[1];

                return $height;
            })

            ->setDefault('real_height', function (Options $options) {
                return (int) round($options['height'] / ProductBox::RATIO);
            })

            ->setDefault('status', function (Options $options) {
                return $this->faker->randomElement([ProductBox::STATUS_NEW, ProductBox::STATUS_ACCEPTED, ProductBox::STATUS_REJECTED]);
            })

            ->setDefault('enabled', function (Options $options) {
                return ProductBox::STATUS_ACCEPTED === $options['status'];
            });
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ProductBox $productBox */
        $productBox = $this->productBoxFactory->createNew();
        $productBox->setRealHeight($options['real_height']);
        $productBox->setHeight($options['height']);
        $productBox->setStatus($options['status']);
        $productBox->setEnabled($options['enabled']);
        $productBox->setProductVariant($options['product_variant']);

        /** @var ProductVariantInterface $variant */
        $variant = $options['product_variant'];
        $productBox->setProduct($variant->getProduct());

        $this->createImage($productBox, $options);

        return $productBox;
    }

    /**
     * @param ProductBox $productBox
     * @param array      $options
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
