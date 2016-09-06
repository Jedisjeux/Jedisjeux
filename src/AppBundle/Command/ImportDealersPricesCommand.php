<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\Entity\Dealer;
use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\InstallerBundle\Command\CommandExecutor;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ImportDealersPricesCommand extends ContainerAwareCommand
{
    /**
     * @var CommandExecutor
     */
    protected $commandExecutor;

    /**
     * @var bool
     */
    private $isErrored = false;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $application = $this->getApplication();
        $application->setCatchExceptions(false);

        $this->commandExecutor = new CommandExecutor(
            $input,
            $output,
            $application
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:dealers-prices:import')
            ->setDescription('Import prices from all dealers')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command import prices from all dealers.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        $dealers = $this->getDealers();

        foreach ($dealers as $step => $dealer) {
            try {
                $output->writeln(sprintf('<comment>Step %d of %d.</comment> <info>%s</info>', $step + 1, count($dealers), $dealer->getCode()));
                $this->commandExecutor->runCommand('app:dealer-prices:import', [
                    'dealer' => $dealer->getCode(),
                    '--filename' => $dealer->getPricesList()->getPath(),
                    '--remove-first-line' => $dealer->getPricesList()->hasHeaders(),
                ], $this->output);
                $output->writeln('');
            } catch (RuntimeException $exception) {
                $this->isErrored = true;

                continue;
            }
        }
    }

    /**
     * @return array|Dealer[]
     */
    protected function getDealers()
    {
        $queryBuilder = $this->getDealerRepository()->createQueryBuilder('o');
        $queryBuilder
            ->join('o.pricesList', 'pricesList')
            ->andWhere('pricesList.active = :active')
            ->setParameter('active', 1);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return EntityRepository
     */
    protected function getDealerRepository()
    {
        return $this->getContainer()->get('app.repository.dealer');
    }
}
