<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/03/2016
 * Time: 16:37
 */

namespace AppBundle\Command\Installer\Data;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\JeuBundle\Entity\Mechanism;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadThemesOfProductsCommand extends ContainerAwareCommand
{
    protected $writeEntityInOutput = false;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:themes-of-products:load')
            ->setDescription('Load themes of products');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));
        $this->deleteGameTaxons();
        $this->insertGameTaxons();
    }

    /**
     * @inheritdoc
     */
    public function deleteGameTaxons()
    {
        $query = <<<EOM
delete productTaxon
from sylius_product_taxon productTaxon
inner join sylius_taxon taxon on taxon.id = productTaxon.taxoninterface_id
where taxon.code like 'theme-%'
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @inheritdoc
     */
    public function insertGameTaxons()
    {
        $query = <<<EOM
insert into sylius_product_taxon(product_id, taxoninterface_id)
select product.id, taxon.id
from jedisjeux.jdj_themelieur old
inner join sylius_product product on product.code = concat('game-', old.jeux_id)
inner join sylius_taxon taxon on taxon.code = concat('theme-', old.theme_id)
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}