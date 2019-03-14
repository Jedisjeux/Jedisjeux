<?php

/**
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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
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
     * @var OutputInterface
     */
    protected $output;

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
            $output->writeln(sprintf('<comment>Step %d of %d.</comment> <info>%s</info>', $step + 1, count($dealers), $dealer->getCode()));

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

                $this->removeDealerPricesFromDealer($dealer);
            }
        }
    }

    /**
     * @return array|Dealer[]
     */
    protected function getDealers()
    {
        return $this->getDealerRepository()->findAll();
    }

    /**
     * @param Dealer $dealer
     *
     * @return int nbRows deleted
     */
    protected function removeDealerPricesFromDealer(Dealer $dealer)
    {
        $query = $this->getManager()->createQuery('delete from App:DealerPrice o where o.dealer = :dealer');

        return $query->execute([
            'dealer' => $dealer,
        ]);
    }

    /**
     * @return EntityRepository|object
     */
    protected function getDealerRepository()
    {
        return $this->getContainer()->get('app.repository.dealer');
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
