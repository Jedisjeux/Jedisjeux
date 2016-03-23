<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/03/16
 * Time: 07:51
 */

namespace AppBundle\Command;

use AppBundle\Entity\GamePlay;
use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use AppBundle\Factory\PostFactory;
use AppBundle\Factory\TopicFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadTopicsOfGamePlayCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:topics-of-game-plays:load')
            ->setDescription('Loading topics of game plays');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $this->deleteGamePlayTopics();

        $gamePlayId = null;
        /** @var Topic $topic */
        $topic = null;

        foreach ($this->getTopics() as $data) {
            $output->writeln(sprintf("Loading <info>%s</info> comment of <info>%s</info> gameplay", $data['id'], $data['game_play_id']));

            /** @var Post $post */
            $post = $this->getPostFactory()->createNew();

            /** Is first post of a topic */
            if ($gamePlayId !== $data['game_play_id']) {
                if (null !== $topic) {
                    $this->getManager()->persist($topic);
                    $this->getManager()->flush();
                    $this->getManager()->clear();
                }

                $topic = $this->getTopicFactory()->createForGamePlay($data['game_play_id']);
                $topic
                    ->setMainPost($post);
            } else {
                // add the answer to the topic
                $topic->addPost($post);
            }

            /** @var CustomerInterface $customer */
            $customer = $this->getCustomerRepository()->find($data['customer_id']);

            $post
                ->setCreatedBy($customer->getUser())
                ->setBody($data['comment']);

            $gamePlayId = $data['game_play_id'];
        }
    }

    protected function deleteGamePlayTopics()
    {

        $queryBuilder = $this->getManager()->createQueryBuilder();
        $queryBuilder
            ->select('post')
            ->from('AppBundle\Entity\Post', 'post')
            ->innerJoin('post.topic', 'topic')
            ->where("topic.title like 'Partie de %'");

        foreach ($queryBuilder->getQuery()->iterate() as $row) {
            $post = $row[0];
            $this->getManager()->remove($post);
            $this->getManager()->flush();
            $this->getManager()->clear();
        }

        $dql = <<<EOM
            delete from AppBundle\Entity\Topic topic
            where topic.title like 'Partie de %'
EOM;

        $queryBuilder = $this->getManager()->createQuery($dql);
        $queryBuilder->execute();
    }

    /**
     * @return array
     */
    protected function getTopics()
    {
        $query = <<<EOM
select      old.id as id,
            customer.id as customer_id,
            old.commentaire as comment,
            old.date as createdAt,
            gamePlay.id as game_play_id
from        jedisjeux.jdj_parties_commentaires old
inner join  jdj_game_play gamePlay
              on gamePlay.code = concat('game-play-', old.partie_id)
inner join  sylius_customer customer
              on customer.code = concat('user-', old.user_id)
order BY    gamePlay.id, old.date;
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return TopicFactory
     */
    protected function getTopicFactory()
    {
        return $this->getContainer()->get('app.factory.topic');
    }

    /**
     * @return PostFactory
     */
    protected function getPostFactory()
    {
        return $this->getContainer()->get('app.factory.post');
    }

    /**
     * @return EntityRepository
     */
    protected function getCustomerRepository()
    {
        return $this->getContainer()->get('sylius.repository.customer');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}
