<?php

/*
 * This file is part of Famileo.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DownloadImagesCommand extends AbstractInstallCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:install:download-images')
            ->setDescription('Download images and avatars files.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> downloads images and avatars files.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('Downloading Jedisjeux images and avatars for environment <info>%s</info>.', $this->getEnvironment()));


        $commands = [
            'app:avatars:download',
            'app:images:download',
        ];

        $this->runCommands($commands, $input, $output);
    }
}
