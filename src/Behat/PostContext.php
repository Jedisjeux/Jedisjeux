<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat;

use App\Entity\Article;
use App\Entity\GamePlay;
use App\Entity\Post;
use App\Entity\Topic;
use App\Factory\PostFactory;
use App\Factory\TopicFactory;
use App\Repository\ProductRepository;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostContext extends DefaultContext
{
    /**
     * @Given /^there are posts:$/
     * @Given /^there are following posts:$/
     * @Given /^the following posts exist:$/
     *
     * @param TableNode $table
     */
    public function thereArePosts(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var Topic $topic */
            $topic = $this->getRepository('topic', 'app')->findOneBy(['title' => $data['topic']]);

            /** @var CustomerInterface $author */
            $author = $this->getRepository('customer')->findOneBy(['email' => $data['author']]);

            /** @var Post $post */
            $post = $this->getFactory('post', 'app')->createNew();
            $post->setTopic($topic);
            $post->setAuthor($author);
            $post->setBody(isset($data['body']) ? $data['body'] : $this->faker->realText());

            $manager->persist($post);
            $manager->flush();
        }
    }

    /**
     * @Given /^game play from "([^""]*)" product and "([^""]*)" author has following comments:$/
     *
     * @param string    $productName
     * @param string    $topicAuthorEmail
     * @param TableNode $table
     */
    public function gamePlayHasComments($productName, $topicAuthorEmail, TableNode $table)
    {
        $manager = $this->getEntityManager();

        /** @var ProductRepository $productRepository */
        $productRepository = $this->getRepository('product');
        /** @var ProductInterface $product */
        $product = $productRepository->findByName($productName, $this->getContainer()->getParameter('locale'))[0];

        /** @var CustomerInterface $topicAuthor */
        $topicAuthor = $this->findOneBy('customer', ['email' => $topicAuthorEmail]);

        /** @var GamePlay $gamePlay */
        $gamePlay = $this->findOneBy('game_play', [
            'product' => $product,
            'author' => $topicAuthor,
        ], 'app');

        /** @var TopicFactory $topicFactory */
        $topicFactory = $this->getFactory('topic', 'app');
        $topic = $topicFactory->createForGamePlay($gamePlay);
        $manager->persist($topic);

        /** @var PostFactory $postFactory */
        $postFactory = $this->getFactory('post', 'app');

        foreach ($table->getHash() as $data) {
            /** @var CustomerInterface $author */
            $author = $this->getRepository('customer')->findOneBy(['email' => $data['author']]);

            /** @var Post $post */
            $post = $postFactory->createForGamePlay($gamePlay);
            $post->setTopic($topic);
            $post->setAuthor($author);
            $post->setBody(isset($data['body']) ? $data['body'] : $this->faker->realText());

            $manager->persist($post);
            $manager->flush();
        }
    }

    /**
     * @Given /^article "([^""]*)" has following comments:$/
     *
     * @param string    $articleTitle
     * @param TableNode $table
     */
    public function articleHasComments($articleTitle, TableNode $table)
    {
        $manager = $this->getEntityManager();

        /** @var Article $article */
        $article = $this->findOneBy('article', ['title' => $articleTitle], 'app');

        /** @var TopicFactory $topicFactory */
        $topicFactory = $this->getFactory('topic', 'app');
        $topic = $topicFactory->createForArticle($article);
        $manager->persist($topic);

        /** @var PostFactory $postFactory */
        $postFactory = $this->getFactory('post', 'app');

        foreach ($table->getHash() as $data) {
            /** @var CustomerInterface $author */
            $author = $this->getRepository('customer')->findOneBy(['email' => $data['author']]);

            /** @var Post $post */
            $post = $postFactory->createForArticle($article);
            $post->setTopic($topic);
            $post->setAuthor($author);
            $post->setBody(isset($data['body']) ? $data['body'] : $this->faker->realText());

            $manager->persist($post);
            $manager->flush();
        }
    }
}
