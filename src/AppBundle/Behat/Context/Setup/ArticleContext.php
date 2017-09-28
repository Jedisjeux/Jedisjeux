<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Setup;

use AppBundle\Behat\Service\SharedStorageInterface;
use AppBundle\Entity\Article;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

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
     * @var ExampleFactoryInterface
     */
    private $articleFactory;

    /**
     * @var RepositoryInterface
     */
    private $articleRepository;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param ExampleFactoryInterface $articleFactory
     * @param RepositoryInterface $articleRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ExampleFactoryInterface $articleFactory,
        RepositoryInterface $articleRepository)
    {
        $this->sharedStorage = $sharedStorage;
        $this->articleFactory = $articleFactory;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Given there is article :title written by :customer
     *
     * @param string $title
     */
    public function thereIsArticleWrittenByCustomer($title, CustomerInterface $customer)
    {
        /** @var Article $article */
        $article = $this->articleFactory->create([
            'title' => $title,
            'author' => $customer,
            'status' => Article::STATUS_PUBLISHED,
        ]);

        $this->articleRepository->add($article);
        $this->sharedStorage->set('article', $article);
    }

    /**
     * @Given there is article :title written by :customer with :status status
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
        ]);

        $this->articleRepository->add($article);
        $this->sharedStorage->set('article', $article);
    }
}
