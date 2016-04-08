<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 13:14
 */

namespace AppBundle\Command\Installer\Data;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
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

    protected $writeEntityInOutput = true;

    protected function createOrReplaceEntity(array $data)
    {
        $entity = $this->getRepository()->find($data['id']);

        if (null === $entity) {
            $entity = $this->createEntityNewInstance();
        }

        $this->populateData($entity, $data);
        if ($this->writeEntityInOutput) {
            $this->output->writeln(sprintf('Loading <comment>%s</comment>', (string)$entity));
        }
        $this->getEntityManager()->persist($entity);

        return $entity;

    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $createdItemCount = 0;
        $updatedItemCount = 0;

        foreach ($this->getRows() as $data) {
            $data = $this->filterData($data);
            $entity = $this->createOrReplaceEntity($data);
            $this->postSetData($entity);
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
    protected function populateData($entity, $data)
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
     *
     * @param array $data
     * @return array
     */
    public function filterData(array $data)
    {
        /**
         * Extend if you want to apply changes on data
         */
        return $data;
    }

    public function postSetData ($entity)
    {
        /**
         * Extend if you want to apply changes on entity
         */
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