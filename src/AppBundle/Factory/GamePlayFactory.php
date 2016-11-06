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

use AppBundle\Entity\GamePlay;
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

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
     * @var EntityRepository
     */
    protected $productRepository;

    /**
     * @var CustomerContextInterface
     */
    protected $customerContext;

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @return GamePlay
     */
    public function createNew()
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = new $this->className;

        return $gamePlay;
    }

    /**
     * Create new game-play for a product
     *
     * @param string $productSlug
     *
     * @return GamePlay
     */
    public function createForProduct($productSlug)
    {
        /** @var ProductInterface $product */
        $product = $this->productRepository->findOneBySlug($productSlug);

        if (null === $product) {
            throw new \InvalidArgumentException(sprintf('Requested product does not exist with slug "%s".', $productSlug));
        }

        /** @var GamePlay $gamePlay */
        $gamePlay = parent::createNew();

        $gamePlay
            ->setProduct($product)
            ->setAuthor($this->customerContext->getCustomer());

        return $gamePlay;
    }

    /**
     * @param ProductRepository $productRepository
     */
    public function setProductRepository($productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param CustomerContext $customerContext
     */
    public function setCustomerContext($customerContext)
    {
        $this->customerContext = $customerContext;
    }
}
