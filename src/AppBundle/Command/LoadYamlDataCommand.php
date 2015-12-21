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
abstract class LoadYamlDataCommand extends ContainerAwareCommand implements LoadYamlDataInterface
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

            $this->getDatabaseConnection()->update($this->getTableName(), array(
                "id" => $data['id'],
            ), array('id' => $entity->getId()));

            $autoIncrement = $data['id'] + 1;
            $this->getDatabaseConnection()->exec("ALTER TABLE ".$this->getTableName()." AUTO_INCREMENT = " . $autoIncrement );
        }

        $this->writeChangesLog($createdItemCount, $updatedItemCount);

    }

    protected function createOrReplaceEntity(array $data)
    {
        $entity = $this->getRepository()->find($data['id']);

        if (null === $entity) {
            $entity = $this->createEntityNewInstance();
        }

        $this->populateData($entity, $data);
        $this->getEntityManager()->persist($entity);

        return $entity;

    }

    /**
     * @param int $createdCount
     * @param int $updatedCount
     */
    protected function writeChangesLog($createdCount, $updatedCount)
    {
        $this->output->writeln(sprintf("<comment>%s</comment> element(s) created", $createdCount));
        $this->output->writeln(sprintf("<comment>%s</comment> element(s) updated", $updatedCount));
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

    /**
     * Populate Data to Entity
     *
     * @param $data
     * @param $entity
     */
    private function populateData($entity, $data)
    {
        $entityReflection = new \ReflectionClass($entity);
        foreach($data as $key=>$value)
        {
            if ($entityReflection->hasProperty($key)) {
                $property = $entityReflection->getProperty($key);
                $property->setAccessible(true);
                if ($property->getName() !== "id") {
                    $property->setValue($entity, $value);
                }
            }
        }
        return $entity;
    }
}