<?php

/*
 * This file is part of jdj project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat;

use AppBundle\Command\ImportDealerPricesCommand;
use AppBundle\Entity\Dealer;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerPriceContext extends DefaultContext
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
     * @var Command
     */
    private $command;

    /**
     * @When /^I run import dealer prices command for "([^""]*)"$/
     */
    public function iRunSyliusCommandLineInstaller($dealerName)
    {
        /** @var Dealer $dealer */
        $dealer = $this->findOneBy('dealer', ['name' => $dealerName], 'app');

        $this->application = new Application($this->getKernel());
        $this->application->add(new ImportDealerPricesCommand());

        $this->command = $this->application->find('app:dealer-prices:import');
        $this->tester = new CommandTester($this->command);

        $this->tester->execute([
            'command' => $this->command,
            'dealer' => $dealer->getCode(),
            '--filename' => $this->getKernel()->getRootDir(). '/../app/resources/test/philibert.csv'
        ]);
    }
}
