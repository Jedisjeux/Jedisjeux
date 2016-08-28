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

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ImportShopperPricesCommand extends ContainerAwareCommand
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->input = $input;
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:shopper-prices:import')
            ->addArgument('file', InputArgument::REQUIRED, 'file to import')
            ->setDescription('Import prices from a shopper')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command import prices from a shopper.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('file')) {
            $file = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please enter a file path:',
                function ($file) {
                    if (empty($file)) {
                        throw new \Exception('File can not be empty');
                    }

                    return $file;
                }
            );

            $input->setArgument('file', $file);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));
    }

    /**
     * @return array
     */
    protected function getCsvData()
    {
        $file = $this->input->getArgument('file');
        $fileData = [];
        $data = [];

        foreach (file($file) as $row) {
            $fileData[] = str_getcsv($row);
        }

        $headers = $fileData[0];
        // remove headers
        unset($fileData[0]);

        foreach ($fileData as $rowData) {
            $data[] = array_combine($headers, $rowData);
        }

        return $data;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
