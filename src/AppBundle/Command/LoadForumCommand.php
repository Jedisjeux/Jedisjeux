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
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
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
            ->setDescription('Loading forum')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>".$this->getDescription()."</comment>");
        //$this->createOrReplaceTaxonomy();
        $this->deletePosts();
        $this->deleteTopics();
        $this->loadTopics();
        $this->loadPosts();
        $this->bbcode2Html();
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }

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

        $taxonomy
            ->setName('forumCategories');

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
insert into jdj_topic (id, title, createdBy_id, createdAt)
select old.topic_id as id,
       old.topic_title as title,
       old.topic_poster as createdBy_id,
      FROM_UNIXTIME(old.topic_time) as createdAt
from jedisjeux.phpbb3_topics old
  inner join fos_user user
    on user.id = old.topic_poster
where forum_id = 51
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
    on topic.id = old.topic_id;
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }


    protected function bbcode2Html()
    {
        /**
         * TODO Paginator
         */

        $queryBuilder = $this->getPostRepository()->createQueryBuilder('o');
        $queryBuilder
            ->andWhere($queryBuilder->expr()->orX(
                'o.body like :quote',
                'o.body like :emoticon',
                'o.body like :image'
            ))
            ->setParameter('quote', '%quote%')
            ->setParameter('emoticon', '%SMILIES%')
            ->setParameter('image', '%img%');

        $paginator = $this->getPostRepository()->getPaginator($queryBuilder);

        $continue = true;

        while ($continue)
        {
            /** @var Post $post */
            foreach ($paginator->getCurrentPageResults() as $post) {
                $bbcode2html = new Bbcode2Html();
                $body = $bbcode2html
                    ->setBody($post->getBody())
                    ->getFilteredBody();

                $post->setBody($body);
            }

            $this->getPostManager()->flush();
            $this->getPostManager()->clear();

            if ($paginator->getNbPages() > $paginator->getCurrentPage()) {
                $paginator->setCurrentPage($paginator->getCurrentPage() + 1);
            } else {
                $continue = false;
            }
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
     * @return EntityRepository
     */
    protected function getPostRepository()
    {
        return $this->getContainer()->get('app.repository.post');
    }
}