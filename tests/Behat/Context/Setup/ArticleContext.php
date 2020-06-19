<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Setup;

use App\Tests\Behat\Service\SharedStorageInterface;
use App\Entity\Article;
use App\Fixture\Factory\ArticleExampleFactory;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ArticleExampleFactory
     */
    private $articleFactory;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param ArticleExampleFactory  $articleFactory
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ArticleExampleFactory $articleFactory,
        EntityManagerInterface $manager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->articleFactory = $articleFactory;
        $this->manager = $manager;
    }

    /**
     * @Given there is an article :title written by :customer
     * @Given there is also an article :title written by :customer
     * @Given there is an article :title written by :customer, published at :date
     * @Given there is also an article :title written by :customer, published at :date
     * @Given there is an article :title written by :customer, published :date
     * @Given there is also an article :title written by :customer, published :date
     *
     * @param string $title
     */
    public function thereIsArticleWrittenByCustomer($title, CustomerInterface $customer, $date = 'now')
    {
        /** @var Article $article */
        $article = $this->articleFactory->create([
            'title' => $title,
            'author' => $customer,
            'status' => Article::STATUS_PUBLISHED,
            'publish_start_date' => $date,
        ]);

        $this->manager->persist($article);
        $this->manager->flush();
        $this->sharedStorage->set('article', $article);
    }

    /**
     * @Given there is an article :title written by :customer with :status status
     *
     * @param string $title
     */
    public function thereIsArticleWrittenByCustomerWithStatus($title, CustomerInterface $customer, $status)
    {
        /** @var Article $article */
        $article = $this->articleFactory->create([
            'title' => $title,
            'author' => $customer,
            'status' => $status,
            'product' => null,
        ]);

        $this->manager->persist($article);
        $this->manager->flush();
        $this->sharedStorage->set('article', $article);
    }

    /**
     * @Given I wrote an article :title with :status status
     *
     * @param string $title
     */
    public function iWroteAnArticleWithStatus($title, $status)
    {
        /** @var Article $article */
        $article = $this->articleFactory->create([
            'title' => $title,
            'author' => $this->sharedStorage->get('customer'),
            'status' => $status,
            'product' => null,
        ]);

        $this->manager->persist($article);
        $this->manager->flush();
        $this->sharedStorage->set('article', $article);
    }

    /**
     * @Given /^(this product) has(?:| also) an article titled "([^"]+)" written by (customer "[^"]+")(?:|, published (\d+) days ago)$/
     */
    public function productHasArticleWrittenByCustomerWithStatus(
        ProductInterface $product,
        string $title,
        CustomerInterface $customer,
        $daysSincePublication = null
    ) {
        /** @var Article $article */
        $article = $this->articleFactory->create([
            'product' => $product,
            'title' => $title,
            'author' => $customer,
            'status' => Article::STATUS_PUBLISHED,
            'publish_start_date' => 'now',
        ]);

        if (null !== $daysSincePublication) {
            $article->setPublishStartDate(new \DateTime('-'.$daysSincePublication.' days'));
        }

        $this->manager->persist($article);
        $this->manager->flush();
        $this->sharedStorage->set('article', $article);
    }

    /**
     * @Given /^(this article) has ("[^"]+" category)$/
     * @Given /^(this article) also has ("[^"]+" category)$/
     */
    public function articleHasCategory(Article $article, TaxonInterface $category)
    {
        $article->setMainTaxon($category);
        $this->manager->flush($article);
    }

    /**
     * @Given /^(this article) has been viewed (\d+) times$/
     */
    public function articleHasViewCount(Article $article, int $viewCount)
    {
        $article->setViewCount($viewCount);
        $this->manager->flush($article);
    }
}
