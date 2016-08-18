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
use Sylius\Bundle\ResourceBundle\Behat\DefaultContext as BaseDefaultContext;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class DefaultContext extends BaseDefaultContext
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

        return $this->getService($applicationName.'.factory.'.$resourceName);
    }
}