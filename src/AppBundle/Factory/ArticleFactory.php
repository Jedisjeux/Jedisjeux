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

use AppBundle\Entity\Article;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleFactory implements FactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var ArticleContentFactory
     */
    protected $articleContentFactory;

    /**
     * @var EntityRepository
     */
    protected $productRepository;

    /**
     * @var CustomerContextInterface
     */
    protected $customerContext;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Article
     */
    public function createNew()
    {
        /** @var Article $article */
        $article = $this->factory->createNew();
        $articleContent = $this->articleContentFactory->createNew();
        $article->setDocument($articleContent);
        $article->setAuthor($this->customerContext->getCustomer());

        return $article;
    }

    /**
     * @param ProductInterface $product
     *
     * @return Article
     */
    public function createForProduct(ProductInterface $product)
    {
        $article = $this->createNew();
        $article->setProduct($product);

        return $article;
    }

    /**
     * @TODO replace by createForProduct when this PR below will be in release of sylius
     * @see https://github.com/Sylius/Sylius/pull/5559
     *
     * @param integer $productId
     *
     * @return Article
     */
    public function createForProductId($productId)
    {
        $product = $this->productRepository->find($productId);

        return $this->createForProduct($product);
    }

    /**
     * @param ProductInterface $product
     *
     * @return Article
     */
    public function createReviewArticleForProduct(ProductInterface $product)
    {
        $article = $this->createForProduct($product);

        $articleContent = $article->getDocument();
        $articleContent->setTitle(sprintf('Critique de %s', (string)$product));

        // TODO set review-article taxon

        return $article;
    }

    /**
     * @param ArticleContentFactory $articleContentFactory
     */
    public function setArticleContentFactory($articleContentFactory)
    {
        $this->articleContentFactory = $articleContentFactory;
    }

    /**
     * @param EntityRepository $productRepository
     */
    public function setProductRepository($productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param CustomerContextInterface $customerContext
     */
    public function setCustomerContext($customerContext)
    {
        $this->customerContext = $customerContext;
    }
}
