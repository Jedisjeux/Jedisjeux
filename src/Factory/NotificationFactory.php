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
use App\Entity\Notification;
use App\Entity\Post;
use App\Entity\ProductBox;
use App\Entity\ProductFile;
use App\Entity\Topic;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Translation\Translator;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class NotificationFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @return Notification
     */
    public function createNew()
    {
        /** @var Notification $notification */
        $notification = new $this->className();

        return $notification;
    }

    /**
     * @param CustomerInterface $customer
     *
     * @return Notification
     */
    public function createForCustomer(CustomerInterface $customer)
    {
        $notification = $this->createNew();
        $notification->setRecipient($customer);

        return $notification;
    }

    /**
     * @param Post              $post
     * @param CustomerInterface $customer
     *
     * @return Notification
     */
    public function createForPost(Post $post, CustomerInterface $customer)
    {
        $topic = $post->getTopic();

        /** @var Notification $notification */
        $notification = $this->createForCustomer($customer);
        $notification->addAuthor($post->getAuthor());
        $notification->setTopic($topic);

        if (null !== $article = $topic->getArticle()) {
            $targetPath = $this->router->generate('app_frontend_article_show', ['slug' => $article->getSlug()]);
        } elseif (null !== $gamePlay = $topic->getGamePlay()) {
            $targetPath = $this->router->generate('app_frontend_game_play_show', ['productSlug' => $gamePlay->getProduct()->getSlug(), 'id' => $gamePlay->getId()]);
        } else {
            $page = $this->calculateTopicNbPages($topic);
            $page = $page > 1 ? $page : null;
            $targetPath = $this->router->generate('app_frontend_post_index_by_topic', ['topicId' => $post->getTopic()->getId(), 'page' => $page]);
        }

        $notification
            ->setTarget($targetPath);

        return $notification;
    }

    /**
     * @param Topic $topic
     *
     * @return int
     */
    protected function calculateTopicNbPages(Topic $topic)
    {
        return (int) ceil($topic->getPostCount() / 10);
    }

    /**
     * @param ProductInterface  $product
     * @param CustomerInterface $customer
     *
     * @return Notification
     */
    public function createForProduct(ProductInterface $product, CustomerInterface $customer)
    {
        /** @var Notification $notification */
        $notification = $this->createForCustomer($customer);
        $notification->setProduct($product);

        return $notification;
    }

    /**
     * @param Article           $article
     * @param CustomerInterface $customer
     *
     * @return Notification
     */
    public function createForArticle(Article $article, CustomerInterface $customer)
    {
        /** @var Notification $notification */
        $notification = $this->createForCustomer($customer);
        $notification->setArticle($article);

        return $notification;
    }

    /**
     * @param ProductBox        $productBox
     * @param CustomerInterface $customer
     *
     * @return Notification
     */
    public function createForProductBox(ProductBox $productBox, CustomerInterface $customer)
    {
        /** @var Notification $notification */
        $notification = $this->createForCustomer($customer);
        $notification->setProductBox($productBox);

        return $notification;
    }

    /**
     * @param ProductFile       $productFile
     * @param CustomerInterface $customer
     *
     * @return Notification
     */
    public function createForProductFile(ProductFile $productFile, CustomerInterface $customer)
    {
        /** @var Notification $notification */
        $notification = $this->createForCustomer($customer);
        $notification->setProductFile($productFile);

        return $notification;
    }

    /**
     * @param Router $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }
}
