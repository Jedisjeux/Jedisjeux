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

use AppBundle\Document\BlockquoteBlock;
use AppBundle\Document\LeftImageBlock;
use AppBundle\Document\RightImageBlock;
use AppBundle\Document\WellImageBlock;
use AppBundle\Entity\Article;
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
     * @var FactoryInterface
     */
    protected $leftImageBlockFactory;

    /**
     * @var FactoryInterface
     */
    protected $rightImageBlockFactory;

    /**
     * @var FactoryInterface
     */
    protected $wellImageBlockFactory;

    /**
     * @var FactoryInterface
     */
    protected $blockquoteBlockFactory;

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

        $articleContent = $article->getDocument();
        $articleContent->setTitle(sprintf('Critique de %s', (string)$product));

        /** @var BlockquoteBlock $introductionBlock */
        $introductionBlock = $this->blockquoteBlockFactory->createNew();
        $introductionBlock->setBody(sprintf('<p>%s</p>', $faker->realText()));

        /** @var LeftImageBlock $materialBlock */
        $materialBlock = $this->leftImageBlockFactory->createWithFakeBody();
        $materialBlock->setTitle('Matériel');

        /** @var RightImageBlock $rulesBlock */
        $rulesBlock = $this->rightImageBlockFactory->createWithFakeBody();
        $rulesBlock->setTitle('Règles');

        /** @var LeftImageBlock $lifetimeBlock */
        $lifetimeBlock = $this->leftImageBlockFactory->createWithFakeBody();
        $lifetimeBlock->setTitle('Durée de vie');

        /** @var WellImageBlock $adviceBlock */
        $adviceBlock = $this->wellImageBlockFactory->createWithFakeBody();
        $adviceBlock->setTitle('Les conseils de jedisjeux');

        $articleContent->addChild($introductionBlock);
        $articleContent->addChild($materialBlock);
        $articleContent->addChild($rulesBlock);
        $articleContent->addChild($lifetimeBlock);
        $articleContent->addChild($adviceBlock);

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

    /**
     * @param FactoryInterface $leftImageBlockFactory
     */
    public function setLeftImageBlockFactory($leftImageBlockFactory)
    {
        $this->leftImageBlockFactory = $leftImageBlockFactory;
    }

    /**
     * @param FactoryInterface $rightImageBlockFactory
     */
    public function setRightImageBlockFactory($rightImageBlockFactory)
    {
        $this->rightImageBlockFactory = $rightImageBlockFactory;
    }

    /**
     * @param FactoryInterface $wellImageBlockFactory
     */
    public function setWellImageBlockFactory($wellImageBlockFactory)
    {
        $this->wellImageBlockFactory = $wellImageBlockFactory;
    }

    /**
     * @param FactoryInterface $blockquoteBlockFactory
     */
    public function setBlockquoteBlockFactory($blockquoteBlockFactory)
    {
        $this->blockquoteBlockFactory = $blockquoteBlockFactory;
    }
}
