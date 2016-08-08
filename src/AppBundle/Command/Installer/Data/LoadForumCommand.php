<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Post;
use AppBundle\Entity\Taxon;
use AppBundle\Repository\TaxonRepository;
use AppBundle\TextFilter\Bbcode2Html;
use AppBundle\Updater\TopicCountByTaxonUpdater;
use Doctrine\ORM\EntityManager;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Model\TaxonomyInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadForumCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:forum:load')
            ->setDescription('Loading forum');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>" . $this->getDescription() . "</comment>");
        $this->deletePosts();
        $this->deleteTopics();
        $this->loadTopics();
        $this->loadPosts();
        $this->bbcode2Html();
        $rootTaxon = $this->getRootTaxon();
        $this->deleteTaxons($rootTaxon);
        $this->loadTaxons($rootTaxon);
        $this->setTopicsMainTaxon();
        $this->updatePostCountByTopic();
        $this->calculateTopicCountByTaxon();
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }

    public function loadTaxons(TaxonInterface $rootTaxon)
    {
        foreach ($this->getTaxons() as $data) {
            $this->createOrReplaceTaxon($data, $rootTaxon);
        }
    }

    protected function createOrReplaceTaxon(array $data, TaxonInterface $rootTaxon)
    {
        $locale = $this->getContainer()->getParameter('locale');

        /** @var TaxonInterface $taxon */
        $taxon = $this->getTaxonRepository()
            ->findOneByNameAndRoot($data['name'], $rootTaxon);

        if (null === $taxon) {
            $taxon = $this->getTaxonFactory()->createNew();
            $taxon->setCurrentLocale($locale);
            $taxon->setFallbackLocale($locale);
        }

        $taxon->setCode('forum-'.$data['id']);
        $taxon->setName($data['name']);
        $taxon->setDescription($data['description'] ?: null);
        $taxon->setParent($rootTaxon);
        $rootTaxon->addChild($taxon);

        $this->getTaxonManager()->persist($taxon);
        $this->getTaxonManager()->flush();

    }

    protected function deleteTaxons(TaxonInterface $rootTaxon)
    {
        return $this->getTaxonManager()
            ->createQuery("delete from AppBundle:Taxon where root = :root")
            ->setParameter('root', $rootTaxon);
    }

    protected function getTaxons()
    {
        $query = <<<EOM
select forum_id as id, forum_name as name, forum_desc as description
from jedisjeux.phpbb3_forums old
where parent_id in (10, 12) or old.forum_id in (20, 21)
order by old.left_id
EOM;

        return $this->getDatabaseConnection()->executeQuery($query);
    }

    protected function setTopicsMainTaxon()
    {
        $query = <<<EOM
update jdj_topic topic
inner join jedisjeux.phpbb3_topics old on old.topic_id = topic.id
inner join Taxon taxon on concat('forum-', old.forum_id) = taxon.code
set topic.mainTaxon_id = taxon.id
where taxon.parent_id is not null
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @return TaxonInterface
     */
    public function getRootTaxon()
    {
        return $this->getContainer()
            ->get('sylius.repository.taxon')
            ->findOneBy(array('code' => Taxon::CODE_FORUM));
    }

    public function deletePosts()
    {
        $query = <<<EOM
update jdj_topic set mainPost_id = null;
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
delete from jdj_post;
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    public function deleteTopics()
    {
        $query = <<<EOM
delete from jdj_topic;
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
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

    /**
     * {@inheritdoc}
     */
    public function loadTopics()
    {
        $query = <<<EOM
        insert into jdj_post(id, createdBy_id, body, createdAt)
select old.post_id as id,
       user.id as createdBy_id,
       concat ('<p>', replace(old.post_text, '\n\n', '</p><p>'), '</p>') as body,
       FROM_UNIXTIME(old.post_time) as createdAt
from jedisjeux.phpbb3_posts old
    inner join jedisjeux.phpbb3_topics old_topic
        on old_topic.topic_first_post_id = old.post_id
    inner join sylius_customer customer
        on customer.code = concat('user-', old.poster_id)
    inner join sylius_user user
        on user.customer_id = customer.id
    inner join jedisjeux.phpbb3_forums forum
        on forum.forum_id = old.forum_id
    where forum.parent_id in (10, 12) or forum.forum_id in (20, 21)
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
insert into jdj_topic (id, title, createdBy_id, createdAt, mainPost_id)
select  old.topic_id as id,
        old.topic_title as title,
        user.id as createdBy_id,
        FROM_UNIXTIME(old.topic_time) as createdAt,
        old.topic_first_post_id as mainPost_id
from jedisjeux.phpbb3_topics old
    inner join sylius_customer customer
        on customer.code = concat('user-', old.topic_poster)
    inner join sylius_user user
        on user.customer_id = customer.id
    inner join jedisjeux.phpbb3_forums forum
            on forum.forum_id = old.forum_id
    inner join jdj_post post
          on post.id = old.topic_first_post_id
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * {@inheritdoc}
     */
    public function loadPosts()
    {
        $query = <<<EOM
        insert into jdj_post(id, topic_id, createdBy_id, body, createdAt)
select old.post_id as id,
       old.topic_id as topic_id,
       user.id as createdBy_id,
       concat ('<p>', replace(old.post_text, '\n\n', '</p><p>'), '</p>') as body,
       FROM_UNIXTIME(old.post_time) as createdAt
from jedisjeux.phpbb3_posts old
  inner join sylius_customer customer
        on customer.code = concat('user-', old.poster_id)
    inner join sylius_user user
        on user.customer_id = customer.id
  inner join jdj_topic topic
    on topic.id = old.topic_id
where old.post_id <> topic.mainPost_id
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }


    protected function bbcode2Html()
    {
        $queryBuilder = $this->getPostRepository()->createQueryBuilder('o');
        $queryBuilder
            ->andWhere($queryBuilder->expr()->orX($queryBuilder->expr()->like('o.body', ':bracket'), $queryBuilder->expr()->like('o.body', ':brace')))
            ->setParameter('bracket', '%[%')
            ->setParameter('brace', '%{%');

        $posts = $queryBuilder->getQuery()->getArrayResult();

        foreach ($posts as $data) {
            $bbcode2html = new Bbcode2Html();
            $body = $data['body'];
            $body = $bbcode2html
                ->setBody($body)
                ->getFilteredBody();

            /** @var Post $post */
            $post = $this->getPostRepository()->find($data['id']);
            $post->setBody($body);
            $this->getPostManager()->flush($post);
            $this->getPostManager()->clear($post);
        }
    }

    protected function updatePostCountByTopic()
    {
        $query = <<<EOM
update jdj_topic topic
    inner join jdj_post post
    on post.topic_id = topic.id
set topic.postCount = (
    select count(0)
  from jdj_post a
    where a.topic_id = topic.id
  group by a.topic_id
)

EOM;

        $this->getDatabaseConnection()->executeQuery($query);

    }

    /**
     * @return TopicCountByTaxonUpdater
     */
    protected function getTopicCountByTaxonUpdater()
    {
        return $this->getContainer()->get('app.updater.topic_count_by_taxon');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return EntityManager
     */
    protected function getPostManager()
    {
        return $this->getContainer()->get('app.manager.post');
    }

    /**
     * @return TaxonRepository
     */
    protected function getTaxonRepository()
    {
        return $this->getContainer()->get('sylius.repository.taxon');
    }

    /**
     * @return EntityManager
     */
    protected function getTaxonomyManager()
    {
        return $this->getContainer()->get('sylius.manager.taxonomy');
    }

    /**
     * @return EntityManager
     */
    protected function getTaxonManager()
    {
        return $this->getContainer()->get('sylius.manager.taxon');
    }

    /**
     * @return Factory
     */
    protected function getTaxonFactory()
    {
        return $this->getContainer()->get('sylius.factory.taxon');
    }

    /**
     * @return EntityRepository
     */
    protected function getPostRepository()
    {
        return $this->getContainer()->get('app.repository.post');
    }
}
