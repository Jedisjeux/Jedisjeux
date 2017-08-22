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
     * @var ExampleFactoryInterface
     */
    private $articleFactory;

    /**
     * @var RepositoryInterface
     */
    private $articleRepository;

    /**
     * ArticleContext constructor.
     *
     * @param ExampleFactoryInterface $articleFactory
     * @param RepositoryInterface $articleRepository
     */
    public function __construct(ExampleFactoryInterface $articleFactory, RepositoryInterface $articleRepository)
    {
        $this->articleFactory = $articleFactory;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Given there is article :title written by :customer
     *
     * @param string $title
     */
    public function thereIsArticle($title, CustomerInterface $customer)
    {
        /** @var Article $article */
        $article = $this->articleFactory->create([
            'title' => $title,
            'author' => $customer,
        ]);

        $this->articleRepository->add($article);
    }
}
