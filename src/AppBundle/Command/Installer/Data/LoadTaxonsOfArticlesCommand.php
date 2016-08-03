<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Taxon;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadTaxonsOfArticlesCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:taxons-of-articles:load')
            ->setDescription('Load taxons of articles');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        /** @var TaxonInterface $rootTaxon */
        $rootTaxon = $this->getRepository()->findOneBy(['code' => Taxon::CODE_ARTICLE]);

        foreach ($this->getRootTaxons() as $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> taxon", $data['name']));

            $taxon = $this->createOrReplaceTaxon($data, $rootTaxon);
            $this->getManager()->persist($taxon);
        }

        $this->getManager()->flush();
    }

    /**
     * @param array $data
     *
     * @return TaxonInterface
     */
    protected function createOrReplaceTaxon(array $data, $rootTaxon)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->getRepository()->findOneBy(['code' => $data['code']]);

        if (null === $taxon) {
            $taxon = $this->getFactory()->createNew();
        }

        $taxon->setParent($rootTaxon);
        $taxon->setCode($data['code']);
        $taxon->setName($data['name']);

        return $taxon;
    }

    /**
     * @return array
     */
    protected function getRootTaxons()
    {
        return [
            [
                'code' => Taxon::CODE_INTERVIEW,
                'name' => 'Interviews',
            ],
            [
                'code' => Taxon::CODE_REVIEW_ARTICLE,
                'name' => 'Critiques',
            ],
        ];
    }

    /**
     * @return Factory
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('sylius.factory.taxon');
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.taxon');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
