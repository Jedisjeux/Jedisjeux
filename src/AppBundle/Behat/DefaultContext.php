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
        $purger = new ORMPurger($this->getService('doctrine.orm.entity_manager'));
        $purger->purge();
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