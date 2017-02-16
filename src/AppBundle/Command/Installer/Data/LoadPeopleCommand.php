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

use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadPeopleCommand extends LoadCommand
{
    protected $writeEntityInOutput = true;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:people:load')
            ->setDescription('Load people');
    }

    public function getRows()
    {
        $query = <<<EOM
SELECT
  old.id,
  CASE
  WHEN old.nom_famille = ''
    THEN old.nom
  ELSE old.nom_famille
  END             AS lastName,
  old.prenom      AS firstName,
  old.siteweb     AS website,
  old.description AS description,
  old.href        AS oldHref
FROM jedisjeux.jdj_personnes old
WHERE old.id <> 14
      AND (old.nom_famille <> '' OR old.nom <> '')
ORDER BY old.id
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
    }

    /**
     * @inheritdoc
     */
    public function filterData(array $data)
    {
//        $data['country'] = !empty($data['country_id']) ? $this
//            ->getEntityManager()
//            ->getRepository('AppBundle:Country')
//            ->findOneBy(array('id' => $data['country_id'])) : null;

        return parent::filterData($data);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln("<comment>Load people</comment>");

        parent::execute($input, $output);
    }

    public function createEntityNewInstance()
    {
        return $this->getFactory()->createNew();
    }

    /**
     * @return Factory|object
     */
    public function getFactory()
    {
        return $this->getContainer()->get('app.factory.person');
    }

    public function getRepository()
    {
        return $this->getContainer()->get('app.repository.person');
    }

    public function getTableName()
    {
        return "jdj_person";
    }
}