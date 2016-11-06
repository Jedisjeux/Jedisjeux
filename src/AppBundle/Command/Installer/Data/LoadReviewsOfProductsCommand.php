<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 15/03/2016
 * Time: 09:38
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductReview;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadReviewsOfProductsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:reviews-of-products:load')
            ->setDescription('Load reviews of products');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getReviews() as $data) {
            $output->writeln(sprintf("Loading <info>%s</info> review of <info>%s</info> product", $data['email'], $data['product_id']));
            $productReview = $this->createOrReplaceProductReview($data);
            $this->getManager()->persist($productReview);
            $this->getManager()->flush();
            $this->getManager()->clear();
        }
    }

    protected function createOrReplaceProductReview(array $data)
    {
        /** @var CustomerInterface $customer */
        $customer = $this->getCustomerRepository()->find($data['customer_id']);

        /** @var Product $product */
        $product = $this->getProductRepository()->find($data['product_id']);

        /** @var ProductReview $productReview */
        $productReview = $this->getRepository()->findOneBy(array(
            'reviewSubject' => $product,
            'author' => $customer,
        ));

        if (null === $productReview) {
            $productReview = $this->getFactory()->createNew();
        }

        $productReview->setTitle($data['title']);
        $productReview->setAuthor($customer);
        $productReview->setReviewSubject($product);
        $productReview->setRating($data['rating']);
        $productReview->setComment($data['comment']);
        $productReview->setStatus(ReviewInterface::STATUS_ACCEPTED);
        $averageRatingCalculator = $this->getContainer()->get('sylius.average_rating_calculator');
        $product->setAverageRating($averageRatingCalculator->calculate($product));

        return $productReview;
    }

    /**
     * @return array
     */
    protected function getReviews()
    {
        $query = <<<EOM
select      customer.email,
            customer.id as customer_id,
            product.id as product_id,
            old.note as rating,
            old.date,
            old.accroche as title,
            case
                when old.avis <> '' then old.avis
                else null
            end as comment
from        jedisjeux.jdj_avis old
inner join  sylius_product product
                on product.code = concat('game-', old.game_id)
inner join  sylius_customer customer
                on customer.code = concat('user-', old.user_id)
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
        return $this->getContainer()->get('sylius.factory.product_review');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('sylius.manager.product_review');
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.product_review');
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
    protected function getProductRepository()
    {
        return $this->getContainer()->get('sylius.repository.product');
    }
}
