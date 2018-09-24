<?php
/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class TransformPhilibertPriceListCommand extends Command
{
    const TMP_FILENAME = '/tmp/philibert.csv';

    /**
     * @var Filesystem
     */
    private $fileSystem;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->fileSystem = new Filesystem();
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:philibert-price-list:transform')
            ->setDescription('Transform prices file from philibert')
            ->addArgument('filename', null, 'filename to transform')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command transform prices file from philibert into our stantard csv file.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));
        $this->removeTmpFile();
        $this->transformData($input);
        $output->writeln('<info>Price list transformed successfully.</info>');
    }

    private function removeTmpFile()
    {
        $this->fileSystem->remove(static::TMP_FILENAME);
    }

    /**
     * @param InputInterface $input
     */
    private function transformData(InputInterface $input)
    {
        $filename = $input->getArgument('filename');

        foreach (file($filename) as $key => $row) {
            $rowData = str_getcsv($row, ';');

            if (
                isset($rowData[1])
                && isset($rowData[2])
                && isset($rowData[5])
                && isset($rowData[7])
                && isset($rowData[8])
            ) {
                $data = [
                    $rowData[1],
                    $rowData[2],
                    $rowData[5],
                    $rowData[7],
                    $rowData[8],
                ];

                file_put_contents(static::TMP_FILENAME, implode(',', $data) . "\n", FILE_APPEND);
            }

        }
    }
}
