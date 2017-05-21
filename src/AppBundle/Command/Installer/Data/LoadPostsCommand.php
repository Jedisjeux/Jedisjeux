<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use AppBundle\Factory\PostFactory;
use AppBundle\Repository\PostRepository;
use AppBundle\TextFilter\Bbcode2Html;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadPostsCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 20;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:posts:load')
            ->setDescription('Load posts');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $i = 0;

        foreach ($this->getPosts() as $data) {
            $output->writeln(sprintf('Load a post of <info>%s</info> topic', $data['topic_title']));
            $topic = $this->createOrReplacePost($data);
            $this->getManager()->persist($topic);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush(); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $this->calculatePostCountByTopics();
    }

    /**
     * @param array $data
     *
     * @return Post
     */
    protected function createOrReplacePost(array $data)
    {
        $code = 'post-' . $data['id'];

        /** @var Post $post */
        $post = $this->getRepository()->findOneBy(['code' => $code]);

        if (null === $post) {
            $post = $this->getFactory()->createNew();
        }

        /** @var Topic $topic */
        $topic = $this->getTopicRepository()->find($data['topic_id']);

        /** @var CustomerInterface $author */
        $author = $this->getCustomerRepository()->find($data['author_id']);

        $bbcode2html = $this->getBbcode2Html();
        $body = $data['body'];
        $body = $bbcode2html
            ->setBody($body)
            ->getFilteredBody();

        $post
            ->setCode($code)
            ->setTopic($topic)
            ->setAuthor($author)
            ->setBody($body)
            ->setCreatedAt(new \DateTime($data['createdAt']))
            ->setArticle($topic->getArticle());

        return $post;
    }

    /**
     * @return array
     */
    protected function getPosts()
    {
        $query = <<<EOM
SELECT
  old.post_id                  AS id,
  topic.id                     AS topic_id,
  customer.id                  AS author_id,
  old.post_text                AS body,
  FROM_UNIXTIME(old.post_time) AS createdAt,
  topic.title                  AS topic_title
FROM jedisjeux.phpbb3_posts old
  INNER JOIN jedisjeux.phpbb3_topics oldTopic
    ON oldTopic.topic_id = old.topic_id
  INNER JOIN sylius_customer customer
    ON customer.code = concat('user-', old.poster_id)
  INNER JOIN jdj_topic topic
    ON topic.code = concat('topic-', old.topic_id)
WHERE old.post_id <> oldTopic.topic_first_post_id
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }

    protected function calculatePostCountByTopics()
    {
        $this->getManager()->getConnection()->executeQuery(<<<EOF
update jdj_topic topic
  inner join
(
  SELECT
    topic_id,
    count(0) AS topic_post_count
  FROM jdj_post post
  GROUP BY post.topic_id
) as a on a.topic_id = topic.id
    set topic.post_count = topic_post_count;
EOF
);
    }

    /**
     * @return Bbcode2Html|object
     */
    protected function getBbcode2Html()
    {
        return $this->getContainer()->get('app.text.filter.bbcode2html');
    }

    /**
     * @return PostFactory|object
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.post');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getCustomerRepository()
    {
        return $this->getContainer()->get('sylius.repository.customer');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getTopicRepository()
    {
        return $this->getContainer()->get('app.repository.topic');
    }

    /**
     * @return PostRepository|object
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.post');
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
