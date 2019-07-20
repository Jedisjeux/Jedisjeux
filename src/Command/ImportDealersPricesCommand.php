<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Command\Installer\CommandExecutor;
use App\Entity\Dealer;
use App\Repository\DealerPriceRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDealersPricesCommand extends Command
{
    /**
     * @var RepositoryInterface
     */
    private $dealerRepository;

    /**
     * @var RepositoryInterface|DealerPriceRepository
     */
    private $dealerPriceRepository;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var CommandExecutor
     */
    private $commandExecutor;

    /**
     * @var bool
     */
    private $isErrored = false;

    /**
     * @param RepositoryInterface $dealerRepository
     * @param RepositoryInterface $dealerPriceRepository
     */
    public function __construct(RepositoryInterface $dealerRepository, RepositoryInterface $dealerPriceRepository)
    {
        $this->dealerRepository = $dealerRepository;
        $this->dealerPriceRepository = $dealerPriceRepository;

        parent::__construct();
    }

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

        /** @var array|Dealer[] $dealers */
        $dealers = $this->dealerRepository->findAll();

        foreach ($dealers as $step => $dealer) {
            if ($dealer->hasPriceList() and $dealer->getPriceList()->isActive()) {
                $output->writeln(sprintf('Import prices for <info>%s</info>.', $dealer->getName()));

                try {
                    $this->commandExecutor->runCommand('app:dealer-prices:import', [
                        'dealer' => $dealer->getCode(),
                        '--filename' => $dealer->getPriceList()->getPath(),
                        '--remove-first-line' => $dealer->getPriceList()->hasHeaders(),
                        '--delimiter' => $dealer->getPriceList()->getDelimiter(),
                        '--utf8' => $dealer->getPriceList()->isUtf8(),
                    ], $output);
                    $output->writeln('');
                } catch (RuntimeException $exception) {
                    $this->isErrored = true;

                    continue;
                } catch (\Exception $exception) {
                    $output->writeln(sprintf('<errror>%s</errror>', $exception->getMessage()));
                    $this->isErrored = true;

                    continue;
                }
            } else {
                $output->writeln(sprintf('Remove prices for <info>%s</info>.', $dealer->getName()));

                $this->dealerPriceRepository->deleteByDealer($dealer);
            }
        }
    }
}
