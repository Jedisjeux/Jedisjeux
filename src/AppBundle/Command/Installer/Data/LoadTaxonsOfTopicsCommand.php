<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Taxon;
use Doctrine\ORM\EntityManager;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadTaxonsOfTopicsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:taxons-of-topics:load')
            ->setDescription('Load taxons of topics');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        // TODO
        $output->writeln(sprintf("<error>TODO</error>", $this->getDescription()));
    }

    /**
     * @return array
     */
    protected function getTaxons()
    {
        $query = <<<EOM
select forum_id as id, forum_name as name, forum_desc as description
from jedisjeux.phpbb3_forums old
order by old.left_id
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }

    /**
     * @return TaxonInterface
     */
    public function getRootTaxon()
    {
        return $this->getContainer()
            ->get('sylius.repository.taxon')
            ->findOneBy(array('code' => Taxon::CODE_FORUM));
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
