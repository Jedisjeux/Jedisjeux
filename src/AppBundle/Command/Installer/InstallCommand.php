<?php

namespace AppBundle\Command\Installer;

use Sylius\Bundle\InstallerBundle\Command\AbstractInstallCommand;
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
            'command' => 'data',
            'message' => 'Data Loading.',
        ],
    ];

    /**
     * @var bool
     */
    private $isErrored = false;

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
        $output->writeln('');

        $this->ensureDirectoryExistsAndIsWritable(self::APP_CACHE, $output);

        foreach ($this->commands as $step => $command) {
            try {
                $output->writeln(sprintf('<comment>Step %d of %d.</comment> <info>%s</info>', $step + 1, count($this->commands), $command['message']));
                $this->commandExecutor->runCommand('app:install:'.$command['command'], [], $output);
                $output->writeln('');
            } catch (RuntimeException $exception) {
                $this->isErrored = true;

                continue;
            }
        }


        $output->writeln($this->getProperFinalMessage());
    }

    /**
     * @return string
     */
    private function getProperFinalMessage()
    {
        if ($this->isErrored) {
            return '<info>Jedisjeux has been installed, but some error occurred.</info>';
        }

        return '<info>Jedisjeux has been successfully installed.</info>';
    }
}
