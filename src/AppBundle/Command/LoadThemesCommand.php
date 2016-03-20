<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 21/12/2015
 * Time: 13:52
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use JDJ\CoreBundle\Entity\EntityRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadThemesCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:themes:load')
            ->setDescription('Load themes');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $taxonomy = $this->createOrReplaceTaxonomy();
        foreach ($this->getRows() as $data) {
            $this->addTaxons($data, $taxonomy->getRoot());
        }
    }

    /**
     * @param array $data
     *
     * @param TaxonInterface $parentTaxon
     */
    protected function addTaxons(array $data, TaxonInterface $parentTaxon)
    {
        $taxon = $this->createOrReplaceTaxon($data, $parentTaxon);
        $this->getManager()->persist($taxon);
        $this->getManager()->flush();

        if (isset($data['children'])) {
            $this->addTaxons($data['children'], $taxon);
        }
    }

    protected function createOrReplaceTaxonomy()
    {
        /** @var TaxonInterface $taxonRoot */
        $taxonRoot = $this->getContainer()
            ->get('sylius.repository.taxon')
            ->findOneBy(array('code' => 'themes'));

        $taxonomy = $taxonRoot ? $taxonRoot->getTaxonomy() : null;

        if (null === $taxonomy) {
            $taxonomy = $this->getContainer()
                ->get('sylius.factory.taxonomy')
                ->createNew();
        }

        $taxonomy->setCode('themes');
        $taxonomy->setName('Thèmes');

        /** @var EntityManager $manager */
        $manager = $this->getContainer()
            ->get('sylius.manager.taxonomy');

        $manager->persist($taxonomy);
        $manager->flush();

        return $taxonomy;
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
        $taxon = $this->getRepository()->findOneBy(array('name' => $data['name']));

        if (null === $taxon) {
            $taxon = $this->getFactory()->createNew();
            $taxon->setCurrentLocale($locale);
            $taxon->setFallbackLocale($locale);
        }

        $taxon->setCode('theme-'.isset($data['id']) ? $data['id'] : uniqid());
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
        return $this->getContainer()->get('kernel')->getRootDir() . '/Resources/initialData/themes.yml';
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
     * @return EntityRepository
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