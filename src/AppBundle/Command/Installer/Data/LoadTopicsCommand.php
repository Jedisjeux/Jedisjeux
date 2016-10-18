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

use AppBundle\Entity\Topic;
use AppBundle\Factory\TopicFactory;
use AppBundle\Repository\TopicRepository;
use AppBundle\TextFilter\Bbcode2Html;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
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

        $topic
            ->setTitle($data['title'])
            ->getMainPost()->setBody($body)
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
select  old.topic_id as id,
        old.topic_title as title,
        customer.id as author_id,
        FROM_UNIXTIME(old.topic_time) as createdAt,
        old.topic_first_post_id as mainPost_id,
        oldMainPost.post_text as body
from jedisjeux.phpbb3_topics old
  inner join jedisjeux.phpbb3_posts oldMainPost
    on oldMainPost.post_id = old.topic_first_post_id
  inner join sylius_customer customer
    on customer.code = concat('user-', old.topic_poster)
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
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
