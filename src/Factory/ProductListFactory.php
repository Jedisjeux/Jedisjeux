<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\ProductList;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductListFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var CustomerContextInterface
     */
    protected $customerContext;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param string $className
     */
    public function __construct($className, CustomerContextInterface $customerContext, TranslatorInterface $translator)
    {
        $this->className = $className;
        $this->customerContext = $customerContext;
        $this->translator = $translator;
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
