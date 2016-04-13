<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/04/2016
 * Time: 17:42
 */

namespace AppBundle\Command\Installer;

use Sylius\Bundle\InstallerBundle\Command\AbstractInstallCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class InstallDataCommand extends AbstractInstallCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:install:data')
            ->setDescription('Install Jedisjeux data.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command installs Jedisjeux data.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('Loading Jedisjeux data for environment <info>%s</info>.', $this->getEnvironment()));

        
        $commands = [
            'app:users:load',
            'app:products:load',
            'app:persons:load',
            'app:forum:load',
            'app:mechanisms:load',
            'app:mechanisms-of-products:load',
            'app:themes:load',
            'app:themes-of-products:load',
            'doctrine:phpcr:repository:init',
            'app:blocks:load',
            'app:articles:load',
            'app:news:load',
            'app:game-libraries:load',
            'app:images-of-products:load',
            'app:images-of-persons:load',
            'app:avatars-of-users:load',
            'app:avatars:download',
            'app:images:download',
        ];

        $this->runCommands($commands, $input, $output);
    }
}