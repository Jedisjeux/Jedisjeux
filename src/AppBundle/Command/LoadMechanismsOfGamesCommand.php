<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 16:32
 */

namespace AppBundle\Command;

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
class LoadMechanismsOfGamesCommand extends ContainerAwareCommand
{
    protected $writeEntityInOutput = false;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:mechanisms-of-games:load')
            ->setDescription('Load mechanisms of games');
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
delete gameTaxon
from jdj_game_taxon gameTaxon
inner join Taxon taxon on taxon.id = gameTaxon.taxoninterface_id
where taxon.code like 'mechanism-%'
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @inheritdoc
     */
    public function insertGameTaxons()
    {
        $query = <<<EOM
insert into jdj_game_taxon(jeu_id, taxoninterface_id)
select jeu.id, taxon.id
from jedisjeux.jdj_mecanismelieur old
inner join jdj_jeu jeu on jeu.id = old.jeux_id
inner join Taxon taxon on taxon.code = concat('mechanism-', old.mecanisme_id)
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