<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 12/04/2016
 * Time: 13:27
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\CustomerListElement;
use AppBundle\Entity\Product;
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
        $this->deleteGameLibraries();
        $this->insertGameLibraries();
    }

    protected function deleteGameLibraries()
    {
        $queryBuiler = $this->getManager()->createQuery('delete from AppBundle:CustomerListElement');
        $queryBuiler->execute();
    }

    protected function insertGameLibraries()
    {
        $query = <<<EOM
insert into jdj_customer_list_element(customerList_id, object_class, object_id)
select      1, :object_class, product.id
from jedisjeux.jdj_ludotheque as old
inner join sylius_customer customer
              on customer.code = concat('user-', old.user_id)
inner join sylius_product product
  on product.code = concat('game-', old.game_id)
where old.user_id=2;

EOM;

        $this->getDatabaseConnection()->executeQuery($query, [
            'object_class' => Product::class,
        ]);
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.customer_list_element');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}