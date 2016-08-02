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
use AppBundle\Repository\TaxonRepository;
use Doctrine\ORM\EntityManager;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadMechanismsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:mechanisms:load')
            ->setDescription('Load mechanisms');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $rootTaxon = $this->getRootTaxon();
        foreach ($this->getRows() as $data) {
            $taxon = $this->createOrReplaceTaxon($data, $rootTaxon);
            $this->getManager()->persist($taxon);
        }

        $this->getManager()->flush();
    }

    /**
     * @return TaxonInterface
     */
    public function getRootTaxon()
    {
        return $this->getContainer()
            ->get('sylius.repository.taxon')
            ->findOneBy(array('code' => Taxon::CODE_MECHANISM));
    }

    /**
     * @param array $data
     * @param TaxonInterface $parentTaxon
     * @return TaxonInterface
     */
    protected function createOrReplaceTaxon(array $data, TaxonInterface $parentTaxon)
    {
        $locale = $this->getContainer()->getParameter('locale');

        /** @var TaxonInterface $taxon */
        $taxon = $this->getRepository()->findOneByNameAndRoot($data['name'], $parentTaxon->getRoot());

        if (null === $taxon) {
            $taxon = $this->getFactory()->createNew();
            $taxon->setCurrentLocale($locale);
            $taxon->setFallbackLocale($locale);
        }

        $taxon->setCode('mechanism-'.$data['id']);
        $taxon->setName($data['name']);
        $taxon->setDescription(isset($data['description']) ? $data['description'] : null);

        //$taxon->setParent($taxonomy->getRoot());
        $parentTaxon->addChild($taxon);

        return $taxon;

    }

    /**
     * @return string
     */
    public function getYAMLFileName()
    {
        return $this->getContainer()->get('kernel')->getRootDir() . '/Resources/initialData/mechanisms.yml';
    }

   /**
     * Parse YAML File
     *
     * @return mixed
     */
    public function getRows()
    {
        $yaml = new Parser();
        return $yaml->parse(file_get_contents($this->getYAMLFileName()));
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return $this->getContainer()->get('sylius.factory.taxon');
    }

    /**
     * @return TaxonRepository
     */
    public function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.taxon');
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->getContainer()->get('sylius.manager.taxon');
    }
}
