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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
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
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:taxons-of-articles:load')
            ->setDescription('Load taxons of articles');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        /** @var TaxonInterface $rootTaxon */
        $rootTaxon = $this->getRepository()->findOneBy(['code' => Taxon::CODE_ARTICLE]);

        foreach ($this->getRootTaxons() as $data) {
            $output->writeln(sprintf('Loading <comment>%s</comment> taxon', $data['name']));

            $taxon = $this->createOrReplaceTaxon($data, $rootTaxon);
            $this->getManager()->persist($taxon);
        }

        $this->getManager()->flush();
    }

    /**
     * @return TaxonInterface
     */
    protected function createOrReplaceTaxon(array $data, TaxonInterface $rootTaxon)
    {
        /** @var Taxon $taxon */
        $taxon = $this->getRepository()->findOneBy(['code' => $data['code']]);

        if (null === $taxon) {
            $taxon = $this->getFactory()->createNew();
        }

        $taxon->setParent($rootTaxon);
        $taxon->setCode($data['code']);
        $taxon->setName($data['name']);
        $taxon->setIconClass(isset($data['icon_class']) ? $data['icon_class'] : null);
        $taxon->setColor(isset($data['color']) ? $data['color'] : null);
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
     * @return array
     */
    protected function getRootTaxons()
    {
        return [
            [
                'code' => Taxon::CODE_NEWS,
                'name' => 'Actualités',
                'color' => 'purple',
                'icon_class' => 'fa fa-newspaper-o',
            ],
            [
                'code' => Taxon::CODE_REVIEW_ARTICLE,
                'name' => 'Critiques',
                'color' => 'red',
                'icon_class' => 'fa fa-bar-chart',
            ],
            [
                'code' => Taxon::CODE_PREVIEWS,
                'name' => 'Previews',
                'color' => 'green',
                'icon_class' => 'fa fa-arrow-right',
            ],
            [
                'code' => Taxon::CODE_IN_THE_BOXES,
                'name' => 'C\'est dans la boîte',
                'color' => 'lblue',
                'icon_class' => 'fa fa-cubes',
            ],
            [
                'code' => Taxon::CODE_REPORT_ARTICLE,
                'name' => 'Reportages',
                'color' => 'black',
                'icon_class' => 'fa fa-camera',
            ],
            [
                'code' => Taxon::CODE_INTERVIEW,
                'name' => 'Interviews',
            ],
            [
                'code' => Taxon::CODE_VIDEO,
                'name' => 'Vidéos',
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
