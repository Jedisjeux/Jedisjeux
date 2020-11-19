<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command\Installer;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\RuntimeException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class InstallCommand extends AbstractInstallCommand
{
    /**
     * @var array
     */
    private $commands = [
        [
            'command' => 'database',
            'message' => 'Setting up the database.',
        ],
        [
            'command' => 'download-images',
            'message' => 'Downloading images and avatars.',
        ],
        [
            'command' => 'assets',
            'message' => 'Installing assets.',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:install')
            ->setDescription('Installs Jedisjeux in your preferred environment.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command installs Jedisjeux.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Installing Jedisjeux...</info>');
        $output->writeln($this->getLogo());

        $this->ensureDirectoryExistsAndIsWritable($this->getContainer()->getParameter('kernel.cache_dir'), $output);

        $errored = false;
        foreach ($this->commands as $step => $command) {
            try {
                $output->writeln(sprintf('<comment>Step %d of %d.</comment> <info>%s</info>', $step + 1, count($this->commands), $command['message']));
                $this->commandExecutor->runCommand('app:install:'.$command['command'], [], $output);
                $output->writeln('');
            } catch (RuntimeException $exception) {
                $errored = true;
            }
        }

        $frontControllerPath = 'prod' === $this->getEnvironment() ? '/' : sprintf('/app_%s.php', $this->getEnvironment());

        $output->writeln($this->getProperFinalMessage($errored));
        $output->writeln(sprintf('You can now open your store at the following path under the website root: <info>%s.</info>', $frontControllerPath));
    }

    /**
     * @return string
     */
    private function getProperFinalMessage($errored)
    {
        if ($errored) {
            return '<info>Jedisjeux has been installed, but some error occurred.</info>';
        }

        return '<info>Jedisjeux has been successfully installed.</info>';
    }

    /**
     * @return string
     */
    private function getLogo()
    {
        return '
        
   $$$$$\                 $$\ $$\                                             
   \__$$ |                $$ |\__|                                            
      $$ | $$$$$$\   $$$$$$$ |$$\  $$$$$$$\ $$\  $$$$$$\  $$\   $$\ $$\   $$\ 
      $$ |$$  __$$\ $$  __$$ |$$ |$$  _____|\__|$$  __$$\ $$ |  $$ |\$$\ $$  |
$$\   $$ |$$$$$$$$ |$$ /  $$ |$$ |\$$$$$$\  $$\ $$$$$$$$ |$$ |  $$ | \$$$$  / 
$$ |  $$ |$$   ____|$$ |  $$ |$$ | \____$$\ $$ |$$   ____|$$ |  $$ | $$  $$<  
\$$$$$$  |\$$$$$$$\ \$$$$$$$ |$$ |$$$$$$$  |$$ |\$$$$$$$\ \$$$$$$  |$$  /\$$\ 
 \______/  \_______| \_______|\__|\_______/ $$ | \_______| \______/ \__/  \__|
                                      $$\   $$ |                              
                                      \$$$$$$  |                              
                                       \______/                               

        ';
    }
}
