<?php

namespace App\Command\Installer\Data;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadStringBlocksCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:string-blocks:load')
            ->setDescription('Load blocks');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        foreach ($this->getBlocks() as $data) {
            $output->writeln(sprintf('Loading <info>%s</info> string block', $data['name']));
        }
    }

    /**
     * @return array
     */
    protected function getBlocks()
    {
        return [
            [
                'name' => 'about',
                'body' => '
<p>Jedisjeux est une association qui rassemble des bénévoles passionnés par les jeux de société. Vous y trouverez des actualités, des critiques, des reportages, des interviews, un forum de discussion, une grande base de données ainsi qu’un calendrier avec les principales dates de sortie des jeux.</p>
                ',
            ],
            [
                'name' => 'head-office',
                'body' => '
    <p class="add">
        16 rue DOM François Plaine<br>
        35137 Bédée</p>
    ',
            ],
        ];
    }
}
