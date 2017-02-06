<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat;

use AppBundle\Command\LoadProductAttributesCommand;
use Doctrine\Bundle\PHPCRBundle\Command\RepositoryInitCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PhpcrContext extends DefaultContext
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
     * @Given /^init doctrine phpcr repository$/
     */
    public function initDoctrinePhpcrRepository()
    {
        $commandName = 'doctrine:phpcr:repository:init';

        $this->application = new Application($this->getKernel());
        $this->application->add(new RepositoryInitCommand());

        $this->command = $this->application->find($commandName);
        $this->tester = new CommandTester($this->command);

        $this->tester->execute(['command' => $commandName]);
    }
}
