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

use App\Entity\Article;
use App\Entity\Block;
use Faker\Factory;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

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
     * @var RepositoryInterface
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

    public function __construct(
        string $className,
        RepositoryInterface $productRepository,
        CustomerContextInterface $customerContext,
        FactoryInterface $blockFactory
    ) {
        $this->className = $className;
        $this->productRepository = $productRepository;
        $this->customerContext = $customerContext;
        $this->blockFactory = $blockFactory;
    }

    /**
     * @return Article
     */
    public function createNew()
    {
        /** @var Article $article */
        $article = new $this->className();
        $article->setAuthor($this->customerContext->getCustomer());

        return $article;
    }

    /**
     * @return Article
     */
    public function createForProduct(ProductInterface $product)
    {
        $article = $this->createNew();
        $article->setProduct($product);

        return $article;
    }

    /**
     * @return Article
     */
    public function createReviewArticleForProduct(ProductInterface $product)
    {
        $faker = Factory::create();
        $article = $this->createForProduct($product);

        $article->setTitle(sprintf('Critique de %s', $product->getName()));

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
}
