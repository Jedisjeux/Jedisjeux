<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 18/03/2016
 * Time: 13:32
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\GamePlay;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadGamePlaysCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:game-plays:load')
            ->setDescription('Loading game plays');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getGamePlays() as $data) {
            $output->writeln(sprintf("Loading <info>%s</info> game play of <info>%s</info> user and <info>%s</info> game", $data['code'], $data['email'], $data['product_id']));
            $gamePlay = $this->createOrReplaceGamePlay($data);
            $this->getManager()->persist($gamePlay);
            $this->getManager()->flush();
            $this->getManager()->clear();
        }

    }

    protected function getGamePlays()
    {
        $query = <<<EOM
select concat('game-play-', old.partie_id) as code,
       product.id as product_id,
       old.date as createdAt,
       customer.id as customer_id,
       customer.email as email
from jedisjeux.jdj_parties old
  inner join sylius_customer customer
    on customer.code = concat('user-', old.user_id)
  inner join sylius_product product
      on product.code = concat('game-', old.game_id)
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
    }

    /**
     * @param array $data
     *
     * @return GamePlay
     */
    protected function createOrReplaceGamePlay(array $data)
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = $this->getRepository()->findOneBy(['code' => $data['code']]);

        if (null === $gamePlay) {
            $gamePlay = $this->getFactory()->createNew();
        }

        /** @var ProductInterface $product */
        $product = $this->getContainer()->get('sylius.repository.product')->find($data['product_id']);

        /** @var CustomerInterface $customer */
        $customer = $this->getContainer()->get('sylius.repository.customer')->find($data['customer_id']);

        $gamePlay
            ->setCode($data['code'])
            ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $data['createdAt']))
            ->setPlayedAt($gamePlay->getCreatedAt())
            ->setProduct($product)
            ->setAuthor($customer);

        return $gamePlay;
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('app.manager.game_play');
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.game_play');
    }

    /**
     * @return Factory
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.game_play');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}