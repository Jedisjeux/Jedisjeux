<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Taxon;
use AppBundle\Entity\Topic;
use AppBundle\Factory\TopicFactory;
use AppBundle\Repository\TaxonRepository;
use AppBundle\Repository\TopicRepository;
use AppBundle\TextFilter\Bbcode2Html;
use AppBundle\Updater\TopicCountByTaxonUpdater;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadTopicsCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 20;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:topics:load')
            ->setDescription('Load topics');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $i = 0;

        foreach ($this->getTopics() as $data) {
            $output->writeln(sprintf('Load <info>%s</info> topic', $data['title']));
            $topic = $this->createOrReplaceTopic($data);
            $this->getManager()->persist($topic);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush(); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $this->calculateTopicCountByTaxon();
        $this->updatePostCountByTopic();
        $this->updateFollowers();
    }

    /**
     * @param array $data
     *
     * @return Topic
     */
    protected function createOrReplaceTopic(array $data)
    {
        $code = 'topic-'.$data['id'];

        $topic = $this->getRepository()->findOneBy(['code' => $code]);

        if (null === $topic) {
            $topic = $this->getFactory()->createNew();
        }

        /** @var CustomerInterface $author */
        $author = $this->getCustomerRepository()->find($data['author_id']);

        $bbcode2html = $this->getBbcode2Html();
        $body = $data['body'];
        $body = $bbcode2html
            ->setBody($body)
            ->getFilteredBody();

        /** @var TaxonInterface $mainTaxon */
        $mainTaxon = $this->getTaxonRepository()->find($data['taxon_id']);

        $mainPost = $topic->getMainPost();
        $mainPost
            ->setBody($body)
            ->setAuthor($author)
            ->setCreatedAt(new \DateTime($data['createdAt']));

        $topic
            ->setMainTaxon($mainTaxon)
            ->setMainPost($mainPost)
            ->setCode($code)
            ->setTitle($data['title'])
            ->setAuthor($author)
            ->setCreatedAt(new \DateTime($data['createdAt']));

        return $topic;
    }

    /**
     * @return array
     */
    protected function getTopics()
    {
        $query = <<<EOM
SELECT
  old.topic_id                   AS id,
  old.topic_title                AS title,
  customer.id                    AS author_id,
  FROM_UNIXTIME(old.topic_time)  AS createdAt,
  old.topic_first_post_id        AS mainPost_id,
  oldMainPost.post_text          AS body,
  taxon.id                       AS taxon_id
FROM jedisjeux.phpbb3_topics old
  INNER JOIN jedisjeux.phpbb3_posts oldMainPost
    ON oldMainPost.post_id = old.topic_first_post_id
  INNER JOIN sylius_customer customer
    ON customer.code = concat('user-', old.topic_poster)
  INNER JOIN jedisjeux.phpbb3_forums forum
    ON forum.forum_id = old.forum_id
  INNER JOIN Taxon taxon
    ON taxon.code = concat('forum-', old.forum_id)
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }

    public function calculateTopicCountByTaxon()
    {
        $taxons = $this->getTaxonRepository()->findChildrenByRootCode(Taxon::CODE_FORUM);

        foreach ($taxons as $taxon) {
            $this->getTopicCountByTaxonUpdater()->update($taxon);
            $this->getManager()->flush();
        }

        $this->getManager()->clear();
    }

    protected function updatePostCountByTopic()
    {
        $this->getManager()->getConnection()->executeQuery(<<<EOM
update jdj_topic topic
    inner join jdj_post post
    on post.topic_id = topic.id
set topic.postCount = (
    select count(0)
  from jdj_post a
    where a.topic_id = topic.id
  group by a.topic_id
)
EOM
        );

    }

    protected function updateFollowers()
    {
        $this->getManager()->getConnection()->executeQuery(<<<EOF
TRUNCATE table jdj_topic_follower
EOF
        );

        $this->getManager()->getConnection()->executeQuery(<<<EOF
INSERT INTO jdj_topic_follower(topic_id, customerinterface_id)
SELECT topic.id as topic_id, customer.id as customer_id
FROM jedisjeux.phpbb3_topics_watch topicWatch
  INNER JOIN jdj_topic topic
    ON topic.code = concat('topic-', topicWatch.topic_id)
  INNER JOIN sylius_customer customer
    ON customer.code = concat('user-', topicWatch.user_id)
WHERE topicWatch.notify_status = 1
EOF
);
    }

    /**
     * @return TopicCountByTaxonUpdater
     */
    protected function getTopicCountByTaxonUpdater()
    {
        return $this->getContainer()->get('app.updater.topic_count_by_taxon');
    }

    /**
     * @return Bbcode2Html
     */
    protected function getBbcode2Html()
    {
        return $this->getContainer()->get('app.text.filter.bbcode2html');
    }

    /**
     * @return TopicFactory
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.topic');
    }

    /**
     * @return EntityRepository
     */
    protected function getCustomerRepository()
    {
        return $this->getContainer()->get('sylius.repository.customer');
    }

    /**
     * @return TaxonRepository
     */
    protected function getTaxonRepository()
    {
        return $this->getContainer()->get('sylius.repository.taxon');
    }

    /**
     * @return TopicRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.topic');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
