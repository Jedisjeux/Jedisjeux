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
use AppBundle\Entity\Block;
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
    protected $blockFactory;

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

        $article->setTitle(sprintf('Critique de %s', (string)$product));

        /** @var Block $materialBlock */
        $materialBlock = $this->blockFactory->createNew();
        $materialBlock
            ->setTitle('Matériel')
            ->setImagePosition(Block::POSITION_LEFT);
        $article->addBlock($materialBlock);

        /** @var Block $rulesBlock */
        $rulesBlock = $this->blockFactory->createNew();
        $rulesBlock
            ->setTitle('Règles')
            ->setImagePosition(Block::POSITION_LEFT);
        $article->addBlock($rulesBlock);

        /** @var Block $lifetimeBlock */
        $lifetimeBlock = $this->blockFactory->createNew();
        $lifetimeBlock
            ->setTitle('Durée de vie')
            ->setImagePosition(Block::POSITION_TOP);
        $article->addBlock($lifetimeBlock);

        /** @var Block $adviceBlock */
        $adviceBlock = $this->blockFactory->createNew();
        $adviceBlock
            ->setTitle('Les conseils de jedisjeux')
            ->setImagePosition(Block::POSITION_TOP)
            ->setClass('well');
        $article->addBlock($adviceBlock);

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
     * @param FactoryInterface $blockFactory
     */
    public function setBlockFactory(FactoryInterface $blockFactory)
    {
        $this->blockFactory = $blockFactory;
    }
}
