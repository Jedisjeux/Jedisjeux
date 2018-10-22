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
use App\Entity\ProductVariant;
use App\Fixture\OptionsResolver\LazyOption;
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
            ->setDefault('product_variant', LazyOption::randomOne($this->productVariantRepository))
            ->setAllowedTypes('product_variant', ['null', 'string', ProductVariantInterface::class])
            ->setNormalizer('product_variant', LazyOption::findOneBy($this->productVariantRepository, 'code'))

            ->setDefault('image', LazyOption::randomOneImage(
                __DIR__ . '/../../../tests/Resources/fixtures/boxes'
            ))

            ->setDefault('height', function (Options $options) {
                $imageSize = getimagesize(realpath($options['image']));
                $height = $imageSize[1];

                return $height;
            })

            ->setDefault('real_height', function (Options $options) {
                return (int) round($options['height'] / ProductBox::RATIO);
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
        $productBox->setRealHeight($options['real_height']);
        $productBox->setHeight($options['height']);


        /** @var ProductVariant $variant */
        $variant = $options['product_variant'];
        $variant->setBox($productBox);
        $productBox->setProduct($variant->getProduct());

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