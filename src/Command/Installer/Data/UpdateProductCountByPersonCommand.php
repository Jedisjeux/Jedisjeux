<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command\Installer\Data;

use App\Entity\Person;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Updater\ProductCountByPersonUpdater;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UpdateProductCountByPersonCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:products:count-by-person')
            ->setDescription('Update product count by person.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command updates product count by person.
EOT
            )
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $this->calculateProductCountByPersons();

        $output->writeln(sprintf("<info>%s</info>", "Product count by taxon have been successfully updated."));
    }

    protected function calculateProductCountByPersons()
    {
        $this->calculateProductCountByPerson();
    }

    protected function calculateProductCountByPerson()
    {
        foreach ($this->createQueryBuilder()->getQuery()->iterate() as $row) {

            /** @var Person $person */
            $person = $row[0];

            $this->getProductCountByPersonUpdater()->update($person);
            $this->getManager()->flush($person);
            $this->getManager()->detach($person);
            $this->getManager()->clear();
        }
    }

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        return $this->getPersonRepository()->createQueryBuilder('o');
    }

    /**
     * @return ProductCountByPersonUpdater|object
     */
    protected function getProductCountByPersonUpdater()
    {
        return $this->getContainer()->get('app.updater.product_count_by_person');
    }

    /**
     * @return ProductRepository|object
     */
    protected function getPersonRepository()
    {
        return $this->getContainer()->get('app.repository.person');
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
