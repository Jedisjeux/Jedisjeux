<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\Person;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\ProductVariantImage;
use Gedmo\Sluggable\Util\Urlizer;
use Sylius\Component\Product\Factory\ProductFactory as BaseProductFactory;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductFactory extends BaseProductFactory
{
    /**
     * @var RepositoryInterface
     */
    private $personRepository;

    /**
     * @var FactoryInterface
     */
    private $productVariantImageFactory;

    /**
     * @var BggProductFactory
     */
    private $bggProductFactory;

    /**
     * @var SlugGeneratorInterface
     */
    private $slugGenerator;

    public function __construct(
        FactoryInterface $factory,
        FactoryInterface $variantFactory,
        FactoryInterface $productVariantImageFactory,
        BggProductFactory $bggProductFactory,
        SlugGeneratorInterface $slugGenerator,
        RepositoryInterface $personRepository
    ) {
        parent::__construct($factory, $variantFactory);

        $this->bggProductFactory = $bggProductFactory;
        $this->productVariantImageFactory = $productVariantImageFactory;
        $this->slugGenerator = $slugGenerator;
        $this->personRepository = $personRepository;
    }

    /**
     * @param string $bggPath
     *
     * @return Product
     */
    public function createFromBgg($bggPath)
    {
        /** @var Product $product */
        $product = parent::createWithVariant();

        $bggProduct = $this->bggProductFactory->createByPath($bggPath);

        $product->setName($bggProduct->getName());
        $product->setSlug($this->slugGenerator->generate($product->getName()));
        $product->setDescription($bggProduct->getDescription());

        if (null !== $releasedAtYear = $bggProduct->getReleasedAtYear()) {
            $releasedAt = \DateTime::createFromFormat('Y-m-d', $releasedAtYear.'-01-01');

            if (false !== $releasedAt) {
                $firstVariant = $product->getFirstVariant();

                $firstVariant->setReleasedAt($releasedAt);
                $firstVariant->setReleasedAtPrecision(ProductVariant::RELEASED_AT_PRECISION_ON_YEAR);
            }
        }

        $product->setMinAge($bggProduct->getAge());
        $product->setMinDuration($bggProduct->getMinDuration());
        $product->setMaxDuration($bggProduct->getMaxDuration());
        $product->setMinPlayerCount($bggProduct->getMinPlayerCount());
        $product->setMaxPlayerCount($bggProduct->getMaxPlayerCount());

        foreach ($bggProduct->getDesigners() as $fullName) {
            $designer = $this->getPersonByFullName($fullName);

            if (null === $designer) {
                continue;
            }

            $product->getFirstVariant()
                ->addDesigner($designer);
        }

        foreach ($bggProduct->getArtists() as $fullName) {
            $artist = $this->getPersonByFullName($fullName);

            if (null === $artist) {
                continue;
            }

            $product->getFirstVariant()
                ->addArtist($artist);
        }

        foreach ($bggProduct->getPublishers() as $fullName) {
            $publisher = $this->getPersonByFullName($fullName);

            if (null === $publisher) {
                continue;
            }

            $product->getFirstVariant()
                ->addPublisher($publisher);
        }

        /** @var ProductVariantImage $mainImage */
        $mainImage = $this->productVariantImageFactory->createNew();
        $mainImage->setMain(true);
        $mainImage->setPath(basename($bggProduct->getImagePath()));
        file_put_contents($mainImage->getAbsolutePath(), file_get_contents($bggProduct->getImagePath()));

        $product->getFirstVariant()->addImage($mainImage);

        return $product;
    }

    /**
     * @param string $fullName
     *
     * @return Person
     */
    protected function getPersonByFullName($fullName)
    {
        $fullName = strtolower($fullName);
        $slug = Urlizer::urlize($fullName, '-');

        /** @var Person $person */
        $person = $this->personRepository->findOneBy(['slug' => $slug]);

        if (null === $person) {
            return null;
        }

        return $person;
    }
}
