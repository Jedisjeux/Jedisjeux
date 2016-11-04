<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/04/2016
 * Time: 17:42
 */

namespace AppBundle\Command\Installer;

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
            'app:root-taxons:load',
            'app:taxons-of-articles:load',
            'app:zones:load',
            'app:blocks:load',
            'app:users:load',
            'app:products:load',
            'app:game-libraries:load',
            'app:persons:load',
            'app:persons-of-products:load',
            'app:zones-of-persons:load',
            'app:taxons-of-topics:load',
            'app:topics:load',
            'app:posts:load',
            'app:mechanisms:load',
            'app:mechanisms-of-products:load',
            'app:themes:load',
            'app:themes-of-products:load',
            'app:target-audiences:load',
            'app:articles:load' => ['--no-debug', '--limit' => 20],
            'app:news:load' => ['--no-debug', '--limit' => 20],
            'app:review-articles:load' => ['--no-debug', '--limit' => 20],
            'app:reviews-of-articles:load',
            'app:game-plays:load',
            'app:players-of-game-plays:load',
            'app:dealers:load',
            'app:players-of-game-plays:load',
            'app:topics-of-game-plays:load',
            'app:reviews-of-products:load',
            'app:images-of-products:load',
            'app:images-of-persons:load',
            'app:avatars-of-users:load',
        ];

        $this->runCommands($commands, $input, $output);
    }
}