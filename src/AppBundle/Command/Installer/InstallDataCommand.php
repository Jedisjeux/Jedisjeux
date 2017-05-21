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
            'app:string-blocks:load',
            //'app:pages:load',
            'app:users:load',
            'app:products:load',
            'app:product-variants:recalculate-positions',
            'app:game-libraries:load',
            'app:product-lists:load',
            'app:boxes-of-products:load',
            'app:people:load',
            'app:persons-of-products:load',
            'app:zones-of-people:load',
            'app:taxons-of-topics:load',
            'app:topics:load',
            'app:posts:load',
            'app:mechanisms:load',
            'app:mechanisms-of-products:load',
            'app:themes:load',
            'app:themes-of-products:load',
            'app:target-audiences:load',
            'app:products:count-by-taxon',
            'app:products:count-by-person',
            'app:articles:load',
            'app:blocks-of-articles:load',
            'app:news:load',
            'app:blocks-of-news:load',
            'app:review-articles:load',
            'app:blocks-of-review-articles:load',
            'app:reviews-of-articles:load',
            'app:game-plays:load',
            'app:players-of-game-plays:load',
            'app:topics-of-game-plays:load',
            'app:dealers:load',
            'app:reviews-of-products:load',
            'app:images-of-products:load',
            'app:images-of-persons:load',
            'app:avatars-of-users:load',
            'app:redirections-for-index-pages:load',
            'app:redirections-for-news:load',
            'app:redirections-for-people:load',
            'app:redirections-for-products:load',
            'app:redirections-for-review-articles:load',
            'app:redirections-for-articles:load',
            'app:dealers-prices:import',
        ];

        $this->runCommands($commands, $output);
    }
}