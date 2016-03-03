<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 19/02/2016
 * Time: 12:38
 */

namespace AppBundle\Command;

use AppBundle\Entity\Post;
use AppBundle\TextFilter\Bbcode2Html;
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
        $taxonomy = $this->createOrReplaceTaxonomy();
        $this->deleteTaxons($taxonomy);
        $this->loadTaxons($taxonomy);
        $this->setTopicsMainTaxon();
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }

    public function loadTaxons(TaxonomyInterface $taxonomy)
    {
        foreach ($this->getTaxons() as $data) {
            $this->createOrReplaceTaxon($data, $taxonomy);
        }

        $this->getTaxonManager()->flush();
    }

    protected function createOrReplaceTaxon(array $data, TaxonomyInterface $taxonomy)
    {
        $locale = $this->getContainer()->getParameter('locale');

        /** @var TaxonInterface $taxon */
        $taxon = $this->getContainer()
            ->get('sylius.repository.taxon')
            ->findOneBy(array('name' => $data['name']));

        if (null === $taxon) {
            $taxon = $this->getTaxonFactory()->createNew();
            $taxon->setCurrentLocale($locale);
            $taxon->setFallbackLocale($locale);
        }

        $taxon->setCode($data['code']);
        $taxon->setName($data['name']);
        $taxon->setParent($taxonomy->getRoot());
        $taxonomy->addTaxon($taxon);

        $this->getTaxonManager()->persist($taxon);

    }

    protected function deleteTaxons(TaxonomyInterface $taxonomy)
    {
        return $this->getTaxonManager()
            ->createQuery("delete from AppBundle:Taxon where taxonomy = :taxonomy ")
            ->setParameter('taxonomy', $taxonomy);
    }

    protected function getTaxons()
    {
        $query = <<<EOM
select forum_id as code, forum_name as name
from jedisjeux.phpbb3_forums old
where parent_id = 10
order by old.left_id
EOM;

        return $this->getDatabaseConnection()->executeQuery($query);
    }

    protected function setTopicsMainTaxon()
    {
        $query = <<<EOM
update jdj_topic topic
inner join jedisjeux.phpbb3_topics old on old.topic_id = topic.id
inner join Taxon taxon on old.forum_id = taxon.code
set topic.mainTaxon_id = taxon.id
where taxon.parent_id is not null
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @return TaxonomyInterface
     */
    public function createOrReplaceTaxonomy()
    {
        /** @var TaxonomyInterface $taxonomy */
        $taxonomy = $this->getContainer()
            ->get('sylius.repository.taxonomy')
            ->findOneBy(array('name' => 'forumCategories'));

        if (null === $taxonomy) {
            $taxonomy = $this->getContainer()
                ->get('sylius.factory.taxonomy')
                ->createNew();
        }

        $taxonomy->setCode('forum-categories');
        $taxonomy->setName('forumCategories');

        /** @var EntityManager $manager */
        $manager = $this->getContainer()
            ->get('sylius.manager.taxonomy');

        $manager->persist($taxonomy);
        $manager->flush();

        return $taxonomy;
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

    /**
     * {@inheritdoc}
     */
    public function loadTopics()
    {
        $query = <<<EOM
        insert into jdj_post(id, createdBy_id, body, createdAt)
select old.post_id as id,
       old.poster_id as createdBy_id,
       concat ('<p>', replace(old.post_text, '\n\n', '</p><p>'), '</p>') as body,
       FROM_UNIXTIME(old.post_time) as createdAt
from jedisjeux.phpbb3_posts old
    inner join jedisjeux.phpbb3_topics old_topic
        on old_topic.topic_first_post_id = old.post_id
  inner join fos_user user
    on user.id = old.poster_id
   inner join jedisjeux.phpbb3_forums forum
            on forum.forum_id = old.forum_id
    where forum.parent_id = 10
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
insert into jdj_topic (id, title, createdBy_id, createdAt, mainPost_id)
select  old.topic_id as id,
        old.topic_title as title,
        old.topic_poster as createdBy_id,
        FROM_UNIXTIME(old.topic_time) as createdAt,
        old.topic_first_post_id as mainPost_id
from jedisjeux.phpbb3_topics old
    inner join fos_user user
            on user.id = old.topic_poster
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
       old.poster_id as createdBy_id,
       concat ('<p>', replace(old.post_text, '\n\n', '</p><p>'), '</p>') as body,
       FROM_UNIXTIME(old.post_time) as createdAt
from jedisjeux.phpbb3_posts old
  inner join fos_user user
    on user.id = old.poster_id
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
            ->andWhere($queryBuilder->expr()->orX(
                'o.body like :quote',
                'o.body like :emoticon',
                'o.body like :url',
                'o.body like :image'
            ))
            ->setParameter('quote', '%[quote%')
            ->setParameter('emoticon', '%SMILIES%')
            ->setParameter('url', '%[url%')
            ->setParameter('image', '%[img%');

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

    /**
     * @return EntityManager
     */
    protected function getPostManager()
    {
        return $this->getContainer()->get('app.manager.post');
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