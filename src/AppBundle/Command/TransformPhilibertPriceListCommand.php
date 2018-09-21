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

class TransformPhilibertPriceListCommand extends Command
{
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
        $this->transformData($input);
        $output->writeln('<info>Price list transformed successfully.</info>');
    }

    private function transformData(InputInterface $input)
    {
        $filename = $input->getArgument('filename');

        foreach (file($filename) as $key => $row) {
            var_dump($row);
            exit;
        }
    }
}
