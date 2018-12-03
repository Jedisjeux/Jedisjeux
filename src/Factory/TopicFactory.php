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
use App\Entity\GamePlay;
use App\Entity\Post;
use App\Entity\Topic;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicFactory implements FactoryInterface
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
     * @var EntityRepository
     */
    protected $gamePlayRepository;

    /**
     * @var FactoryInterface
     */
    protected $postFactory;

    /**
     * @param string $className
     */
    public function __construct(
        string $className,
        CustomerContextInterface $customerContext,
        RepositoryInterface $gamePlayRepository,
        FactoryInterface $postFactory
    ) {
        $this->className = $className;
        $this->customerContext = $customerContext;
        $this->gamePlayRepository = $gamePlayRepository;
        $this->postFactory = $postFactory;
    }

    /**
     * @return Topic
     */
    public function createNew()
    {
        /** @var Topic $topic */
        $topic = new $this->className();
        $topic->setAuthor($this->customerContext->getCustomer());

        /** @var Post $mainPost */
        $mainPost = $this->postFactory->createNew();
        $topic->setMainPost($mainPost);

        return $topic;
    }

    /**
     * @param GamePlay $gamePlay
     *
     * @return Topic
     */
    public function createForGamePlay(GamePlay $gamePlay)
    {
        /** @var Topic $topic */
        $topic = $this->createNew();
        $topic->setMainPost(null); // topic for game play has no main post

        $gamePlay
            ->setTopic($topic);

        $topic->setTitle('Partie de '.(string) $gamePlay->getProduct());
        $topic->setAuthor($gamePlay->getAuthor());

        return $topic;
    }

    /**
     * @param Article $article
     *
     * @return Topic
     */
    public function createForArticle(Article $article)
    {
        /** @var Topic $topic */
        $topic = $this->createNew();
        $topic->setMainPost(null); // topic for article has no main post

        $article
            ->setTopic($topic);

        $topic->setTitle($article->getTitle());
        $topic->setAuthor($article->getAuthor());

        return $topic;
    }

    /**
     * {@inheritdoc}
     */
    public function createForTaxon(TaxonInterface $taxon)
    {
        $topic = $this->createNew();
        $topic->setMainTaxon($taxon);

        return $topic;
    }
}
