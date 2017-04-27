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

use AppBundle\Entity\CustomerList;
use AppBundle\Entity\CustomerListElement;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductList;
use AppBundle\Factory\ProductListFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadGameLibrariesCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 20;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:game-libraries:load')
            ->setDescription('Loading game libraries');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>" . $this->getDescription() . "</comment>");

        $i = 0;

        foreach ($this->getLists() as $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> game library", $data['email']));

            $list = $this->createOrReplaceList($data);
            $this->getManager()->persist($list);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush();
                $this->getManager()->clear();
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $this->deleteItems();
        $this->insertItems();

        $output->writeln(sprintf("<info>%s</info>", "Game library has been successfully loaded."));
    }

    /**
     * @param array $data
     *
     * @return ProductList
     */
    protected function createOrReplaceList(array $data)
    {
        $owner = $this->getContainer()->get('sylius.repository.customer')->find($data['id']);

        /** @var ProductList $list */
        $list = $this->getRepository()->findOneBy([
            'code' => ProductList::CODE_GAME_LIBRARY,
            'owner' => $owner,
        ]);

        if (null === $list) {
            $list = $this->getFactory()->createForCode(ProductList::CODE_GAME_LIBRARY);
            $list->setOwner($owner);
        }

        return $list;
    }

    /**
     * @return array
     */
    protected function getLists()
    {
        return $this->getManager()->getConnection()->fetchAll(
            <<<EOF
select      distinct customer.id, customer.email
from jedisjeux.jdj_ludotheque as old
inner join sylius_customer customer
              on customer.code = concat('user-', old.user_id)
EOF
        );
    }

    protected function deleteItems()
    {
        $queryBuilder = $this->getManager()->createQuery('delete from AppBundle:ProductListItem');
        $queryBuilder->execute();
    }

    protected function insertItems()
    {
        $query = <<<EOM
INSERT INTO jdj_product_list_item (list_id, product_id, created_at, updated_at)
  SELECT
    list.id,
    product.id,
    DATE_ADD(DATE('2000-01-01 00:00:00'), INTERVAL old.ludo_id SECOND),
    DATE_ADD(DATE('2000-01-01 00:00:00'), INTERVAL old.ludo_id SECOND)
  FROM jedisjeux.jdj_ludotheque AS old
    INNER JOIN sylius_customer customer
      ON customer.code = concat('user-', old.user_id)
    INNER JOIN sylius_product_variant variant
      ON variant.code = concat('game-', old.game_id)
    INNER JOIN sylius_product product
      ON product.id = variant.product_id
    INNER JOIN jdj_product_list list
      ON list.code = :code
         AND list.owner_id = customer.id;
EOM;

        $this->getManager()->getConnection()->executeQuery($query, [
            'code' => ProductList::CODE_GAME_LIBRARY,
        ]);
    }

    /**
     * @return ProductListFactory|object
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.product_list');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.product_list');
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
