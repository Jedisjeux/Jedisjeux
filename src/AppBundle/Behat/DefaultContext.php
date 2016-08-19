<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 25/09/15
 * Time: 00:46
 */

namespace AppBundle\Behat;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class DefaultContext extends DefaultApiContext
{
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
        $stmt = $em
            ->getConnection()
            ->prepare('SET foreign_key_checks = 1;');
        $stmt->execute();
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
}