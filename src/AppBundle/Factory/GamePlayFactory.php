<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 08/04/16
 * Time: 00:39
 */

namespace AppBundle\Factory;

use AppBundle\Entity\GamePlay;
use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\UserBundle\Context\CustomerContext;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\Factory;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayFactory extends Factory
{
    /**
     * @var EntityRepository
     */
    protected $productRepository;

    /**
     * @var CustomerContext
     */
    protected $customerContext;

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
        $product = $this->productRepository->findOneBy(['slug' => $productSlug]);

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
     * @param EntityRepository $productRepository
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
