<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory;

use AppBundle\Entity\Person;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductVariant;
use AppBundle\Utils\BggProduct;
use Doctrine\ORM\EntityRepository;
use Gedmo\Sluggable\Util\Urlizer;
use Sylius\Component\Product\Factory\ProductFactory as BaseProductFactory;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductFactory extends BaseProductFactory
{
    /**
     * @var EntityRepository
     */
    protected $personRepository;

    /**
     * @param EntityRepository $personRepository
     */
    public function setPersonRepository(EntityRepository $personRepository)
    {
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

        $bggProduct = new BggProduct($bggPath);

        $product->setName($bggProduct->getName());
        $product->setDescription($bggProduct->getDescription());

        if (null !== $releasedAtYear = $bggProduct->getReleasedAtYear()) {
            $releasedAt = \DateTime::createFromFormat('Y-m-d', $releasedAtYear . '-01-01');

            if (false !== $releasedAt) {
                $product->getFirstVariant()
                    ->setReleasedAt($releasedAt)
                    ->setReleasedAtPrecision(ProductVariant::RELEASED_AT_PRECISION_ON_YEAR);
            }
        }


        $product->setAgeMin($bggProduct->getAge());
        $product->setDurationMin($bggProduct->getDuration());
        $product->setDurationMax($bggProduct->getDuration());
        $product->setJoueurMin($bggProduct->getNbJoueursMin());
        $product->setJoueurMax($bggProduct->getNbJoueursMax());

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
