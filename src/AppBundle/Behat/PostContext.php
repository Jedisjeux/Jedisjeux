<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat;

use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

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
    public function thereAreTopics(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            /** @var Topic $topic */
            $topic = $this->getRepository('topic', 'app')->findOneBy(['title' => $data['topic']]);

            /** @var CustomerInterface $author */
            $author = $this->getRepository('customer')->findOneBy(['email' => $data['author']]);

            /** @var Post $post */
            $post = $this->getFactory('post', 'app')->createNew();
            $post
                ->setTopic($topic)
                ->setAuthor($author)
                ->setBody(isset($data['body']) ? $data['body'] : $this->faker->realText());

            $manager->persist($post);
            $manager->flush();
        }
    }
}

