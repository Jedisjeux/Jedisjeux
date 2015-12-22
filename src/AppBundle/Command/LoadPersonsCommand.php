<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 13:13
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityRepository;
use JDJ\LudographieBundle\Entity\Personne;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadPersonsCommand extends LoadCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:persons:load')
            ->setDescription('Load persons');
    }

    protected function getPersons()
    {
        $query = <<<EOM
select      old.id,
            case
                when old.nom_famille = '' then old.nom
                else old.nom_famille
            end as nom,
            old.prenom as prenom,
            old.siteweb as siteWeb,
            old.description as description,
            pays.id as pays_id
from        jedisjeux.jdj_personnes old
left join   jdj_pays pays
                on CONVERT(pays.libelle USING utf8) = CONVERT(old.nationnalite USING utf8)
where       old.id <>14
and         (old.nom_famille <> '' or old.nom <> '')
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln("<comment>Load persons</comment>");

        $createdItemCount = 0;
        $updatedItemCount = 0;

        $rows = $this->getPersons();
        foreach ($rows as $data) {
            $entity = $this->createOrReplaceEntity($data, false);

            if (null === $entity->getId()) {
                $createdItemCount++;
            } else {
                $updatedItemCount++;
            }

            $this->getEntityManager()->flush();
            $this->getEntityManager()->clear();

            $this->getDatabaseConnection()->update($this->getTableName(), array(
                "id" => $data['id'],
            ), array('id' => $entity->getId()));

            $autoIncrement = $data['id'] + 1;
            $this->getDatabaseConnection()->exec("ALTER TABLE " . $this->getTableName() . " AUTO_INCREMENT = " . $autoIncrement);
        }
        $this->writeChangesLog($createdItemCount, $updatedItemCount);
    }

    public function createEntityNewInstance()
    {
        return new Personne();
    }

    public function getRepository()
    {
        return $this->getEntityManager()->getRepository('JDJLudographieBundle:Personne');
    }

    public function getTableName()
    {
        return "jdj_personne";
    }
}