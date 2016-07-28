<?php

/*
 * This file is part of VPS.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory;

use AppBundle\Entity\Article;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\Factory;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleFactory extends Factory
{
    /**
     * @var ArticleContentFactory
     */
    protected $articleContentFactory;

    /**
     * @var EntityRepository
     */
    protected $productRepository;

    /**
     * @return Article
     */
    public function createNew()
    {
        /** @var Article $article */
        $article = parent::createNew();
        $articleContent = $this->articleContentFactory->createNew();
        $article->setDocument($articleContent);

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

        $article = $this->createNew();
        $article->setProduct($product);

        return $article;
    }

    /**
     * @param ProductInterface $product
     *
     * @return Article
     */
    public function createForTest(ProductInterface $product)
    {
        $article = $this->createForProduct($product);

        $faker = \Faker\Factory::create();

        $articleContent = $article->getDocument();
        $articleContent->setTitle($faker->title);

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
}
