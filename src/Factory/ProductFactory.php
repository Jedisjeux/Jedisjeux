<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\BggProduct;
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
 *
 * @extends BaseProductFactory<Product>
 */
class ProductFactory extends BaseProductFactory
{
    /**
     * @param BggProductFactory        $bggProductFactory,
     * @param FactoryInterface<Person> $personFactory
     */
    public function __construct(
        FactoryInterface $factory,
        FactoryInterface $variantFactory,
        private FactoryInterface $productVariantImageFactory,
        private BggProductFactory $bggProductFactory,
        private SlugGeneratorInterface $slugGenerator,
        private RepositoryInterface $personRepository,
        private FactoryInterface $personFactory
    ) {
        parent::__construct($factory, $variantFactory);
    }

    /**
     * @param string $bggPath
     *
     * @return Product
     */
    public function createFromBgg($bggPath): Product
    {
        return $this->createFromBggProduct($this->bggProductFactory->createByPath($bggPath));
    }

    /**
     * @param string $payload data sent by the BoardGameGeek bookmarklet
     *
     * @return Product
     */
    public function createFromBggPayload($payload): Product
    {
        return $this->createFromBggProduct($this->bggProductFactory->createFromPayload((string) $payload));
    }

    private function createFromBggProduct(BggProduct $bggProduct): Product
    {
        /** @var Product $product */
        $product = parent::createWithVariant();

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
            $designer = $this->getOrCreatePerson($fullName, false);

            if (null === $designer) {
                continue;
            }

            $product->getFirstVariant()
                ->addDesigner($designer);
        }

        foreach ($bggProduct->getArtists() as $fullName) {
            $artist = $this->getOrCreatePerson($fullName, false);

            if (null === $artist) {
                continue;
            }

            $product->getFirstVariant()
                ->addArtist($artist);
        }

        foreach ($bggProduct->getPublishers() as $fullName) {
            $publisher = $this->getOrCreatePerson($fullName, true);

            if (null === $publisher) {
                continue;
            }

            $product->getFirstVariant()
                ->addPublisher($publisher);
        }

        if (null !== $imagePath = $bggProduct->getImagePath()) {
            /** @var ProductVariantImage $mainImage */
            $mainImage = $this->productVariantImageFactory->createNew();
            $mainImage->setMain(true);
            $mainImage->setPath(basename(parse_url($imagePath, PHP_URL_PATH)));
            file_put_contents($mainImage->getAbsolutePath(), file_get_contents($imagePath));

            $product->getFirstVariant()->addImage($mainImage);
        }

        return $product;
    }

    /**
     * Finds the person by its BoardGameGeek name, or creates it when it does not exist yet.
     * New persons are saved right away because the product form references them by their identifier.
     */
    private function getOrCreatePerson(string $fullName, bool $isCompany): ?Person
    {
        // BGG suffixes homonyms with a roman numeral, such as "Jeff Smith (I)"
        $fullName = trim((string) preg_replace('/\s*\([IVX]+\)$/', '', trim($fullName)));

        // BGG placeholders, such as "(Uncredited)", "(Unknown)" or "(Self-Published)"
        if ('' === $fullName || str_starts_with($fullName, '(')) {
            return null;
        }

        /** @var Person|null $person */
        $person = $this->personRepository->findOneBy(['slug' => Urlizer::urlize(mb_strtolower($fullName), '-')]);

        if (null !== $person) {
            return $person;
        }

        $names = preg_split('/\s+/', $fullName) ?: [$fullName];
        $hasFirstName = !$isCompany && count($names) > 1;

        /** @var Person $person */
        $person = $this->personFactory->createNew();
        $person->setFirstName($hasFirstName ? mb_substr(array_shift($names), 0, 50) : null);
        $person->setLastName(mb_substr($hasFirstName ? implode(' ', $names) : $fullName, 0, 50));

        $this->personRepository->add($person);

        return $person;
    }
}
