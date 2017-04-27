<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/03/16
 * Time: 07:51
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\GamePlay;
use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use AppBundle\Factory\PostFactory;
use AppBundle\Factory\TopicFactory;
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
class LoadTopicsOfGamePlaysCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 20;

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

        $i = 0;

        foreach ($this->getPosts() as $data) {
            $output->writeln(sprintf("Load <info>%s</info> post for <info>%s</info> game play", $data['code'], $data['gamePlayId']));

            $post = $this->createOrReplacePost($data);

            $this->getManager()->persist($post->getTopic());
            $this->getManager()->persist($post);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush(); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;

            $this->getManager()->flush();
            $this->getManager()->clear();
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $this->updatePostCountByTopic();
    }

    /**
     * @param array $data
     *
     * @return Post
     */
    protected function createOrReplacePost(array $data)
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = $this->getGamePlayRepository()->find($data['gamePlayId']);

        /** @var CustomerInterface $author */
        $author = $this->getCustomerRepository()->find($data['customerId']);

        if (null === $topic = $gamePlay->getTopic()) {
            $topic = $this->getTopicFactory()->createForGamePlay($gamePlay);
            $topic->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $data['createdAt']));
        }

        /** @var Post $post */
        $post = $this->getPostRepository()->findOneBy(['code' => $data['code']]);

        if (null === $post) {
            $post = $this->getPostFactory()->createForGamePlay($gamePlay);
            $post->setTopic($topic);
        }

        $bbcode2html = $this->getBbcode2Html();
        $body = $data['body'];
        $body = $bbcode2html
            ->setBody($body)
            ->getFilteredBody();

        $post
            ->setCode($data['code'])
            ->setBody($body)
            ->setAuthor($author)
            ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $data['createdAt']));

        return $post;
    }

    /**
     * @return array
     */
    protected function getPosts()
    {
        $query = <<<EOM
SELECT
  concat('article-post-', old.id) AS code,
  customer.id                     AS customerId,
  old.commentaire                 AS body,
  old.date                        AS createdAt,
  gamePlay.id                     AS gamePlayId
FROM jedisjeux.jdj_parties_commentaires old
  INNER JOIN jdj_game_play gamePlay
    ON gamePlay.code = concat('game-play-', old.partie_id)
  INNER JOIN sylius_customer customer
    ON customer.code = concat('user-', old.user_id)
ORDER BY gamePlay.id, old.date
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }

    protected function updatePostCountByTopic()
    {
        $this->getManager()->getConnection()->executeQuery(<<<EOM
UPDATE jdj_topic topic
  INNER JOIN jdj_post post
    ON post.topic_id = topic.id
SET topic.post_count = (
  SELECT count(0)
  FROM jdj_post a
  WHERE a.topic_id = topic.id
  GROUP BY a.topic_id
)

EOM
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
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return TopicFactory|object
     */
    protected function getTopicFactory()
    {
        return $this->getContainer()->get('app.factory.topic');
    }

    /**
     * @return PostFactory|object
     */
    protected function getPostFactory()
    {
        return $this->getContainer()->get('app.factory.post');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getPostRepository()
    {
        return $this->getContainer()->get('app.repository.post');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getGamePlayRepository()
    {
        return $this->getContainer()->get('app.repository.game_play');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getCustomerRepository()
    {
        return $this->getContainer()->get('sylius.repository.customer');
    }
}
