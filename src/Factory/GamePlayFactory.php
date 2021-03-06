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

use App\Context\CustomerContext;
use App\Entity\GamePlay;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var CustomerContextInterface
     */
    protected $customerContext;

    /**
     * @param string $className
     * @param ProductRepositoryInterface $productRepository
     * @param CustomerContext $customerContext
     */
    public function __construct(
        string $className,
        ProductRepositoryInterface $productRepository,
        CustomerContext $customerContext
    ) {
        $this->className = $className;
        $this->productRepository = $productRepository;
        $this->customerContext = $customerContext;
    }

    /**
     * @return GamePlay
     */
    public function createNew()
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = new $this->className();

        $gamePlay->setAuthor($this->customerContext->getCustomer());

        return $gamePlay;
    }

    /**
     * Create new game-play for a product.
     *
     * @param string $locale
     * @param string $productSlug
     *
     * @return GamePlay
     */
    public function createForProduct(string $locale, string $productSlug)
    {
        /** @var ProductInterface $product */
        $product = $this->productRepository->findOneBySlug($locale, $productSlug);

        if (null === $product) {
            throw new \InvalidArgumentException(sprintf('Requested product does not exist with slug "%s".', $productSlug));
        }

        /** @var GamePlay $gamePlay */
        $gamePlay = $this->createNew();

        $gamePlay->setProduct($product);

        return $gamePlay;
    }
}
