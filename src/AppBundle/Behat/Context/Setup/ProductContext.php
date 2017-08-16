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

use AppBundle\Entity\Product;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductContext implements Context
{
    /**
     * @var ExampleFactoryInterface
     */
    protected $productFactory;

    /**
     * @var RepositoryInterface
     */
    protected $productRepository;

    /**
     * PersonContext constructor.
     *
     * @param ExampleFactoryInterface $productFactory
     * @param RepositoryInterface $productRepository
     */
    public function __construct(ExampleFactoryInterface $productFactory, RepositoryInterface $productRepository)
    {
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
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
        ]);

        $this->productRepository->add($product);
    }
}
