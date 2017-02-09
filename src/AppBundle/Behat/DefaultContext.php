<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 25/09/15
 * Time: 00:46
 */

namespace AppBundle\Behat;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\Bundle\PHPCRBundle\Command\WorkspacePurgeCommand;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class DefaultContext extends DefaultApiContext
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var CommandTester
     */
    private $tester;

    /**
     * @var ContainerAwareCommand
     */
    private $command;

    /**
     * @BeforeScenario
     */
    public function purgeDatabase(BeforeScenarioScope $scope)
    {
        /** @var EntityManager $em */
        $em = $this->getEntityManager();
        $stmt = $em
            ->getConnection()
            ->prepare('SET foreign_key_checks = 0;');
        $stmt->execute();
        $purger = new ORMPurger($this->getService('doctrine.orm.entity_manager'));
        $purger->purge();
        $this->purgePhpcrDatabase();
        $stmt = $em
            ->getConnection()
            ->prepare('SET foreign_key_checks = 1;');
        $stmt->execute();
    }

    public function purgePhpcrDatabase()
    {
        $commandName = 'doctrine:phpcr:workspace:purge';

        $this->application = new Application($this->getKernel());
        $this->application->add(new WorkspacePurgeCommand());

        $this->command = $this->application->find($commandName);
        $this->tester = new CommandTester($this->command);

        $this->tester->execute(['command' => $commandName, '--force' => true]);
    }

    /**
     * @param string $resourceName
     * @param null|string $applicationName
     *
     * @return FactoryInterface
     */
    protected function getFactory($resourceName, $applicationName = null)
    {
        $applicationName = null === $applicationName ? $this->applicationName : $applicationName;

        /** @var FactoryInterface $factory */
        $factory = $this->getService($applicationName.'.factory.'.$resourceName);

        return $factory;
    }

    /**
     * @param string $type
     * @param array $criteria
     * @param null|string $applicationName
     *
     * @return object
     */
    protected function findOneBy($type, array $criteria, $applicationName = null)
    {
        $applicationName = null === $applicationName ? $this->applicationName : $applicationName;

        $resource = $this
            ->getRepository($type, $applicationName)
            ->findOneBy($criteria)
        ;

        if (null === $resource) {
            throw new \InvalidArgumentException(
                sprintf('%s for criteria "%s" was not found.', str_replace('_', ' ', ucfirst($type)), serialize($criteria))
            );
        }

        return $resource;
    }

    /**
     * @param string $resourceName
     * @param null|string $applicationName
     *
     * @return RepositoryInterface
     */
    protected function getRepository($resourceName, $applicationName = null)
    {
        $applicationName = null === $applicationName ? $this->applicationName : $applicationName;

        /** @var RepositoryInterface $repository */
        $repository = $this->getService($applicationName.'.repository.'.$resourceName);

        return $repository;
    }

    /**
     * @return ObjectManager
     */
    protected function getDocumentManager()
    {
        return $this->getService('doctrine_phpcr')->getManager();
    }
}