<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 13:14
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
abstract class LoadCommand extends ContainerAwareCommand implements LoadCommandInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;

    protected function createOrReplaceEntity(array $data, $writeOutput = true)
    {
        $entity = $this->getRepository()->find($data['id']);

        if (null === $entity) {
            $entity = $this->createEntityNewInstance();
        }

        $this->populateData($entity, $data);
        if ($writeOutput) {
            $this->output->writeln(sprintf('Loading <comment>%s</comment>', (string)$entity));
        }
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
     * Populate Data to Entity
     *
     * @param $data
     * @param $entity
     */
    private function populateData($entity, $data)
    {
        $entityReflection = new \ReflectionClass($entity);
        foreach ($data as $key => $value) {
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