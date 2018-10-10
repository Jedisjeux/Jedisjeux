<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Setup;

use App\Behat\Service\SharedStorageInterface;
use App\Entity\Person;
use App\Entity\Product;
use App\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var ExampleFactoryInterface
     */
    protected $productFactory;

    /**
     * @var RepositoryInterface
     */
    protected $productRepository;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param ExampleFactoryInterface $productFactory
     * @param RepositoryInterface $productRepository
     * @param EntityManager $manager
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ExampleFactoryInterface $productFactory,
        RepositoryInterface $productRepository,
        EntityManager $manager
    )
    {
        $this->sharedStorage = $sharedStorage;
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->manager = $manager;
    }


    /**
     * @Given there is a product :name
     * @Given there is a product :name, created at :date
     *
     * @param string $name
     */
    public function productHasName($name, $date = 'now')
    {
        /** @var Product $product */
        $product = $this->productFactory->create([
            'name' => $name,
            'created_at' => $date,
            'status' => Product::PUBLISHED,
            'mechanisms' => [],
            'themes' => [],
            'designers' => [],
            'artists' => [],
            'publishers' => [],
            'min_duration' => null,
            'max_duration' => null,
        ]);

        $this->productRepository->add($product);
        $this->sharedStorage->set('product', $product);
    }

    /**
     * @Given there is a product :name, released :date
     * @Given there is also a product :name, released :date
     *
     * @param string $name
     */
    public function productHasNameAndReleaseDate($name, $date = 'now')
    {
        /** @var Product $product */
        $product = $this->productFactory->create([
            'name' => $name,
            'released_at' => $date,
            'status' => Product::PUBLISHED,
            'mechanisms' => [],
            'themes' => [],
            'designers' => [],
            'artists' => [],
            'publishers' => [],
            'min_duration' => null,
            'max_duration' => null,
        ]);

        $this->productRepository->add($product);
        $this->sharedStorage->set('product', $product);
    }

    /**
     * @Given there is a product :name with :status status
     *
     * @param string $name
     */
    public function productHasNameWithStatus($name, $status)
    {
        /** @var Product $product */
        $product = $this->productFactory->create([
            'name' => $name,
            'status' => str_replace(' ', '_', $status),
            'mechanisms' => [],
            'themes' => [],
            'designers' => [],
            'artists' => [],
            'publishers' => [],
        ]);

        $this->productRepository->add($product);
        $this->sharedStorage->set('product', $product);
    }

    /**
     * @Given /^(this product) has ("[^"]+" mechanism)$/
     * @Given /^(this product) also has ("[^"]+" mechanism)$/
     */
    public function productHasMechanism(Product $product, TaxonInterface $mechanism)
    {
        $product->addMechanism($mechanism);
        $this->manager->flush($product);
    }

    /**
     * @Given /^(this product) has ("[^"]+" theme)$/
     * @Given /^(this product) also has ("[^"]+" theme)$/
     */
    public function productHasTheme(Product $product, TaxonInterface $theme)
    {
        $product->addTheme($theme);
        $this->manager->flush($product);
    }

    /**
     * @Given /^(this product) is designed by ("[^"]+" person)$/
     * @Given /^(this product) is also designed by ("[^"]+" person)$/
     */
    public function productHasDesigner(Product $product, Person $person)
    {
        $product->getFirstVariant()->addDesigner($person);
        $this->manager->flush($product->getFirstVariant());
    }

    /**
     * @Given /^(this product) is drawn by ("[^"]+" person)$/
     * @Given /^(this product) is also drawn by ("[^"]+" person)$/
     */
    public function productHasArtist(Product $product, Person $person)
    {
        $product->getFirstVariant()->addArtist($person);
        $this->manager->flush($product->getFirstVariant());
    }

    /**
     * @Given /^(this product) is published by ("[^"]+" person)$/
     * @Given /^(this product) is also published by ("[^"]+" person)$/
     */
    public function productHasPublisher(Product $product, Person $person)
    {
        $product->getFirstVariant()->addPublisher($person);
        $this->manager->flush($product->getFirstVariant());
    }

    /**
     * @Given /^(this product) takes (\d+) minutes$/
     */
    public function productTakesMinutes(Product $product, int $minDuration)
    {
        $product->setMinDuration($minDuration);
        $this->manager->flush($product);
    }

    /**
     * @Given /^(this product) can be played from (\d+) years$/
     */
    public function productCanBePlayedFromAge(Product $product, int $minAge)
    {
        $product->setMinAge($minAge);
        $this->manager->flush($product);
    }

    /**
     * @Given /^(this product) can be played from (\d+) to (\d+) players$/
     */
    public function productCanBePlayedFromToPlayers(Product $product, int $minPlayerCount, int $maxPlayerCount)
    {
        $product->setMinPlayerCount($minPlayerCount);
        $product->setMaxPlayerCount($maxPlayerCount);
        $this->manager->flush($product);
    }

    /**
     * @Given /^(this product) can only be played with (\d+) players$/
     */
    public function productCanOnlyBePlayedWithPlayerCount(Product $product, int $playerCount)
    {
        $product->setMinPlayerCount($playerCount);
        $product->setMaxPlayerCount($playerCount);
        $this->manager->flush($product);
    }
}
