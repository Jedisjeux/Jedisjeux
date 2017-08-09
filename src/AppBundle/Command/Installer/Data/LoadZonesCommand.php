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
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadZonesCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:zones:load')
            ->setDescription('Load zones');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $rootTaxon = $this->getRootTaxon();
        $this->addTaxons($this->getRows(), $rootTaxon);
    }

    /**
     * @param array $taxons
     * @param TaxonInterface $parentTaxon
     */
    protected function addTaxons(array $taxons, TaxonInterface $parentTaxon)
    {
        foreach ($taxons as $data) {
            $this->output->writeln(sprintf("Loading <info>%s</info> zone", $data['name']));
            $taxon = $this->createOrReplaceTaxon($data, $parentTaxon);
            $this->getManager()->persist($taxon);
            $this->getManager()->flush();

            if (isset($data['children'])) {
                $this->addTaxons($data['children'], $taxon);
            }
        }

    }

    /**
     * @param array $data
     * @param TaxonInterface $parentTaxon
     *
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

        $code = isset($data['id']) ? 'zone-'.$data['id'] : uniqid();

        $taxon->setCode($code);
        $taxon->setName($data['name']);
        $taxon->setDescription(isset($data['description']) ? $data['description'] : null);

        $parentTaxon->addChild($taxon);

        $taxon->setSlug($this->getTaxonSlugGenerator()->generate($taxon));

        return $taxon;

    }

    /**
     * @return TaxonSlugGeneratorInterface|object
     */
    protected function getTaxonSlugGenerator()
    {
        return $this->getContainer()->get('sylius.generator.taxon_slug');
    }

    /**
     * @return TaxonInterface
     */
    public function getRootTaxon()
    {
        return $this->getContainer()
            ->get('sylius.repository.taxon')
            ->findOneBy(array('code' => Taxon::CODE_ZONE));
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
     * @return string
     */
    public function getYAMLFileName()
    {
        return $this->getContainer()->get('kernel')->getRootDir() . '/Resources/initialData/zones.yml';
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
