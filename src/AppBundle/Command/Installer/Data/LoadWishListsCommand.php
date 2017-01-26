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
class LoadWishListsCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 20;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:wish-lists:load')
            ->setDescription('Loading wish lists');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>" . $this->getDescription() . "</comment>");

        $i = 0;

        foreach ($this->getLists() as $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> wish list", $data['email']));

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

        $this->insertItems();

        $output->writeln(sprintf("<info>%s</info>", "Wish lists has been successfully loaded."));
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
            'code' => ProductList::CODE_WISHES,
            'owner' => $owner,
        ]);

        if (null === $list) {
            $list = $this->getFactory()->createForCode(ProductList::CODE_WISHES);
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
SELECT
  customer.id,
  customer.email
FROM jedisjeux.jdj_liste old
  INNER JOIN sylius_customer customer
    ON customer.code = concat('user-', old.id_user)
WHERE id_type = 5;
EOF
        );
    }

    protected function insertItems()
    {
        $query = <<<EOM
INSERT INTO jdj_product_list_item (list_id, product_id, createdAt, updatedAt)
  SELECT
    list.id,
    product.id,
    item.dateadd,
    item.dateadd
  FROM jedisjeux.jdj_liste AS old
    INNER JOIN jedisjeux.jdj_liste_element item
      ON item.id_liste = old.id_liste
    INNER JOIN sylius_customer customer
      ON customer.code = concat('user-', old.id_user)
    INNER JOIN sylius_product_variant variant
      ON variant.code = concat('game-', item.id_element)
    INNER JOIN sylius_product product
      ON product.id = variant.product_id
    INNER JOIN jdj_product_list list
      ON list.code = :code
         AND list.owner_id = customer.id;
EOM;

        $this->getManager()->getConnection()->executeQuery($query, [
            'code' => ProductList::CODE_WISHES,
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
