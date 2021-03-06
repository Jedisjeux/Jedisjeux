<?php

/*
 * This file is part of Jedisjeux project.
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
class LoadRootTaxonsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:root-taxons:load')
            ->setDescription('Load root taxons');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        foreach ($this->getRootTaxons() as $data) {
            $output->writeln(sprintf('Loading <comment>%s</comment> root taxon', $data['name']));

            $rootTaxon = $this->createOrReplaceRootTaxon($data);
            $this->getManager()->persist($rootTaxon);
        }

        $this->getManager()->flush();
        $output->writeln(sprintf('<info>%s root taxons successfully loaded</info>', count($this->getRootTaxons())));
    }

    /**
     * @param array $data
     *
     * @return TaxonInterface
     */
    protected function createOrReplaceRootTaxon(array $data)
    {
        /** @var Taxon $rootTaxon */
        $rootTaxon = $this->getRepository()->findOneBy(['code' => $data['code']]);

        if (null === $rootTaxon) {
            $rootTaxon = $this->getFactory()->createNew();
        }

        $rootTaxon->setCode($data['code']);
        $rootTaxon->setName($data['name']);
        $rootTaxon->setIconClass(isset($data['icon_class']) ? $data['icon_class'] : null);
        $rootTaxon->setSlug($this->getTaxonSlugGenerator()->generate($rootTaxon));

        return $rootTaxon;
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
                'code' => Taxon::CODE_FORUM,
                'name' => 'Forum',
            ],
            [
                'code' => Taxon::CODE_THEME,
                'name' => 'Thèmes',
                'icon_class' => 'fa fa-picture-o',
            ],
            [
                'code' => Taxon::CODE_MECHANISM,
                'name' => 'Mécanismes',
                'icon_class' => 'fa fa-cogs',
            ],
            [
                'code' => Taxon::CODE_TARGET_AUDIENCE,
                'name' => 'Cibles',
                'icon_class' => 'glyphicon glyphicon-screenshot',
            ],
            [
                'code' => Taxon::CODE_ZONE,
                'name' => 'Zones',
            ],
            [
                'code' => Taxon::CODE_ARTICLE,
                'name' => 'Catégories',
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
