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

use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleReview;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadReviewsOfArticlesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:reviews-of-articles:load')
            ->setDescription('Load reviews of articles');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getReviews() as $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> review of <comment>%s</comment> article", $data['email'], $data['article_id']));
            $articleReview = $this->createOrReplaceArticleReview($data);
            $this->getManager()->persist($articleReview);
            $this->getManager()->flush();
            $this->getManager()->clear();
        }
    }

    /**
     * @param array $data
     *
     * @return ArticleReview
     */
    protected function createOrReplaceArticleReview(array $data)
    {
        /** @var CustomerInterface $customer */
        $customer = $this->getCustomerRepository()->find($data['customer_id']);

        /** @var Article $article */
        $article = $this->getArticleRepository()->find($data['article_id']);

        /** @var ArticleReview $articleReview */
        $articleReview = $this->getRepository()->findOneBy(array(
            'reviewSubject' => $article,
            'author' => $customer,
        ));

        if (null === $articleReview) {
            $articleReview = $this->getFactory()->createNew();
        }

        $articleReview->setAuthor($customer);
        $articleReview->setReviewSubject($article);
        $articleReview->setRating($data['rating']);
        $articleReview->setComment($data['comment']);
        $averageRatingCalculator = $this->getContainer()->get('sylius.review.calculator.average_rating');
        $article->setAverageRating($averageRatingCalculator->calculate($article));

        return $articleReview;
    }

    /**
     * @return array
     */
    protected function getReviews()
    {
        $query = <<<EOM
select      
  customer.email,
  customer.id as customer_id,
  article.id as article_id,
  old.note as rating,
  old.avis as comment
from        jedisjeux.jdj_tests_avis old
  inner join  sylius_product product
    on product.code = concat('game-', old.game_id)
  inner join  sylius_customer customer
    on customer.code = concat('user-', old.user_id)
inner join jdj_article article
    on article.product_id = product.id
inner join phpcr_nodes node
    on node.path = article.documentId
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }

    /**
     * @return FactoryInterface
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.article_review');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.article_review');
    }

    /**
     * @return EntityRepository
     */
    protected function getCustomerRepository()
    {
        return $this->getContainer()->get('sylius.repository.customer');
    }

    /**
     * @return EntityRepository
     */
    protected function getArticleRepository()
    {
        return $this->getContainer()->get('app.repository.article');
    }
}
