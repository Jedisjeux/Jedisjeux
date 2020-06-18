<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Transform;

use App\Entity\ProductList;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
final class ProductListContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $productListRepository;

    /**
     * @param RepositoryInterface $productListRepository
     */
    public function __construct(RepositoryInterface $productListRepository)
    {
        $this->productListRepository = $productListRepository;
    }

    /**
     * @Transform :productList
     */
    public function getProductListByName($name)
    {
        /** @var ProductList $productList */
        $productList = $this->productListRepository->findOneBy(['name' => $name]);

        Assert::notNull(
            $productList,
            sprintf('Product list with name "%s" does not exist', $name)
        );

        return $productList;
    }
}
