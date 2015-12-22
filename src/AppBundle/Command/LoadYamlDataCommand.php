<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 21/12/2015
 * Time: 13:23
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
abstract class LoadYamlDataCommand extends LoadCommand implements LoadYamlDataInterface, LoadCommandInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $createdItemCount = 0;
        $updatedItemCount = 0;

        $rows = $this->parse();
        foreach ($rows as $data) {
            $entity = $this->createOrReplaceEntity($data);
            if (null === $entity->getId()) {
                $createdItemCount ++;
            } else {
                $updatedItemCount ++;
            }

            $this->getEntityManager()->flush();
            $this->getEntityManager()->clear();

            $this->getDatabaseConnection()->update($this->getTableName(), array(
                "id" => $data['id'],
            ), array('id' => $entity->getId()));

            $autoIncrement = $data['id'] + 1;
            $this->getDatabaseConnection()->exec("ALTER TABLE ".$this->getTableName()." AUTO_INCREMENT = " . $autoIncrement );
        }

        $this->writeChangesLog($createdItemCount, $updatedItemCount);

    }

    /**
     * Parse YAML File
     *
     * @return mixed
     */
    public function parse()
    {
        $yaml = new Parser();
        return $yaml->parse(file_get_contents($this->getYAMLFileName()));
    }
}