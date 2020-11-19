<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command\Installer\Data;

use App\Entity\Taxon;
use App\Repository\TaxonRepository;
use Doctrine\ORM\EntityManager;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadTargetAudiencesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:target-audiences:load')
            ->setDescription('Load target-audiences');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

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
            ->findOneBy(['code' => Taxon::CODE_TARGET_AUDIENCE]);
    }

    /**
     * @return TaxonInterface
     */
    protected function createOrReplaceTaxon(array $data, TaxonInterface $parentTaxon)
    {
        $locale = $this->getContainer()->getParameter('locale');

        /** @var Taxon $taxon */
        $taxon = $this->getRepository()->findOneByNameAndRoot($data['name'], $parentTaxon->getRoot());

        if (null === $taxon) {
            $taxon = $this->getFactory()->createNew();
            $taxon->setCurrentLocale($locale);
            $taxon->setFallbackLocale($locale);
        }

        $taxon->setCode('target-audiences-'.$data['id']);
        $taxon->setName($data['name']);
        $taxon->setColor(isset($data['color']) ? $data['color'] : null);
        $taxon->setIconClass(isset($data['icon_class']) ? $data['icon_class'] : null);
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
     * @return string
     */
    public function getYAMLFileName()
    {
        return $this->getContainer()->get('kernel')->getRootDir().'/Resources/initialData/target_audiences.yml';
    }

    /**
     * Parse YAML File.
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
