<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\ProductList;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductListFactory implements FactoryInterface
{
    /**
     * @param string $className
     */
    public function __construct(
        private $className,
        protected CustomerContextInterface $customerContext,
        protected TranslatorInterface $translator
    ) {
    }

    /**
     * @return ProductList
     */
    public function createNew()
    {
        /** @var ProductList $productList */
        $productList = new $this->className();
        $productList
            ->setOwner($this->customerContext->getCustomer());

        return $productList;
    }

    /**
     * @param $code
     *
     * @return ProductList
     */
    public function createForCode($code)
    {
        $productList = $this->createNew();

        $productList->setCode($code);
        $productList->setName($this->translator->trans('app.ui.'.$code));

        return $productList;
    }
}
