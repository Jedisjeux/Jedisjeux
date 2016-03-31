<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 31/03/16
 * Time: 00:14
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadZonesOfPersonsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:zones-of-persons:load')
            ->setDescription('Load zones of persons');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));
        $this->deleteTaxons();
        $this->insertTaxons();
    }

    /**
     * @inheritdoc
     */
    public function deleteTaxons()
    {
        $query = <<<EOM
delete personTaxon
from jdj_person_taxon personTaxon
inner join Taxon taxon on taxon.id = personTaxon.taxoninterface_id
inner join sylius_taxon_translation taxonTranslation on taxonTranslation.translatable_id = taxon.id
where taxonTranslation.permalink like 'zones%'
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @inheritdoc
     */
    public function insertTaxons()
    {
        $query = <<<EOM
insert into jdj_person_taxon(person_id, taxoninterface_id)
select person.id, taxonTranslation.translatable_id
from jedisjeux.jdj_personnes old
  inner join jdj_person person
      on person.id = old.id
  inner join sylius_taxon_translation taxonTranslation
    on convert(taxonTranslation.name USING utf8) like convert(old.nationnalite USING utf8)
    where taxonTranslation.permalink like 'zones%'
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
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
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}