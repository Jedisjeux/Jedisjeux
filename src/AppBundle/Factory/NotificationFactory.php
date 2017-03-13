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

use AppBundle\Entity\Article;
use AppBundle\Entity\Notification;
use AppBundle\Entity\Post;
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
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @return Notification
     */
    public function createNew()
    {
        /** @var Notification $notification */
        $notification = new $this->className;

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
     * @param Post $post
     * @param CustomerInterface $customer
     *
     * @return Notification
     */
    public function createForPost(Post $post, CustomerInterface $customer)
    {
        /** @var Notification $notification */
        $notification = $this->createForCustomer($customer);

        $topic = $post->getTopic();

        if (null !== $article = $topic->getArticle()) {
            $targetPath = $this->router->generate('app_frontend_article_show', ['slug' => $article->getSlug()]);
        } elseif (null !== $gamePlay = $topic->getGamePlay()) {
            $targetPath = $this->router->generate('app_frontend_game_play_show', ['productSlug' => $gamePlay->getProduct()->getSlug(), 'id' => $gamePlay->getId()]);
        } else {
            $targetPath = $this->router->generate('app_frontend_post_index_by_topic', ['topicId' => $post->getTopic()->getId()]);
        }


        /** TODO only set topic and move target and message in a post notification manager */
        $notification
            ->setTopic($post->getTopic())
            ->setTarget($targetPath)
            ->setMessage($this->translator->trans('text.notification.topic_reply', [
                '%USERNAME%' => $post->getAuthor(),
                '%TOPIC_NAME%' => $post->getTopic()->getTitle(),
            ]));

        return $notification;
    }

    /**
     * @param ProductInterface $product
     * @param CustomerInterface $customer
     *
     * @return Notification
     */
    public function createForProduct(ProductInterface $product, CustomerInterface $customer)
    {
        /** @var Notification $notification */
        $notification = $this->createForCustomer($customer);
        $notification
            ->setProduct($product);

        return $notification;
    }

    /**
     * @param Article $article
     * @param CustomerInterface $customer
     *
     * @return Notification
     */
    public function createForArticle(Article $article, CustomerInterface $customer)
    {
        /** @var Notification $notification */
        $notification = $this->createForCustomer($customer);
        $notification
            ->setArticle($article);

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
