<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Setup;

use AppBundle\Behat\Service\SharedStorageInterface;
use AppBundle\Entity\Product;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
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
     * @Given there is product :name
     *
     * @param string $name
     */
    public function ProductHasName($name)
    {
        /** @var Product $product */
        $product = $this->productFactory->create([
            'name' => $name,
            'status' => Product::PUBLISHED,
        ]);

        $this->productRepository->add($product);
        $this->sharedStorage->set('product', $product);
    }

    /**
     * @Given /^(this product) has ("[^"]+" mechanism)$/
     */
    public function ProductHasMechanism(Product $product, TaxonInterface $mechanism)
    {
        $product->addMechanism($mechanism);
        $this->manager->flush($mechanism);
    }

    /**
     * @Given /^(this product) has ("[^"]+" theme)$/
     */
    public function ProductHasTheme(Product $product, TaxonInterface $theme)
    {
        $product->addTheme($theme);
        $this->manager->flush($theme);
    }
}
