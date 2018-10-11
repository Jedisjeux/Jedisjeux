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

use App\Entity\Article;
use App\Entity\Block;
use Doctrine\ORM\EntityRepository;
use Faker\Factory;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleFactory implements FactoryInterface
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
     * @var FactoryInterface
     */
    protected $blockFactory;

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @return Article
     */
    public function createNew()
    {
        /** @var Article $article */
        $article = new $this->className;
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
     * @param ProductInterface $product
     *
     * @return Article
     */
    public function createReviewArticleForProduct(ProductInterface $product)
    {
        $faker = Factory::create();
        $article = $this->createForProduct($product);

        $article->setTitle(sprintf('Critique de %s', (string)$product));

        /** @var Block $materialBlock */
        $materialBlock = $this->blockFactory->createNew();
        $materialBlock->setTitle('Matériel');
        $materialBlock->setImagePosition(Block::POSITION_LEFT);
        $article->addBlock($materialBlock);

        /** @var Block $rulesBlock */
        $rulesBlock = $this->blockFactory->createNew();
        $rulesBlock->setTitle('Règles');
        $rulesBlock->setImagePosition(Block::POSITION_LEFT);
        $article->addBlock($rulesBlock);

        /** @var Block $lifetimeBlock */
        $lifetimeBlock = $this->blockFactory->createNew();
        $lifetimeBlock->setTitle('Durée de vie');
        $lifetimeBlock->setImagePosition(Block::POSITION_TOP);
        $article->addBlock($lifetimeBlock);

        /** @var Block $adviceBlock */
        $adviceBlock = $this->blockFactory->createNew();
        $adviceBlock->setTitle('Les conseils de jedisjeux');
        $adviceBlock->setImagePosition(Block::POSITION_TOP);
        $adviceBlock->setClass('well');
        $article->addBlock($adviceBlock);

        // TODO set review-article taxon

        return $article;
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

    /**
     * @param FactoryInterface $blockFactory
     */
    public function setBlockFactory(FactoryInterface $blockFactory)
    {
        $this->blockFactory = $blockFactory;
    }
}
