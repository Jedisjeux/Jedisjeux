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

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateProductOnProductImagesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:product-images:update-product')
            ->setDescription('Update product on product images.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command updates product on product images.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        $this->calculateProductCountByTaxons();

        $output->writeln(sprintf('<info>%s</info>', 'Product count by taxon have been successfully updated.'));
    }

    protected function calculateProductCountByTaxons()
    {
        $this->calculateProductCountByTaxonCode(Taxon::CODE_MECHANISM);
        $this->calculateProductCountByTaxonCode(Taxon::CODE_THEME);
        $this->calculateProductCountByTaxonCode(Taxon::CODE_TARGET_AUDIENCE);
    }

    /**
     * @param string $rootCode
     */
    protected function calculateProductCountByTaxonCode($rootCode)
    {
        $taxons = $this->getTaxonRepository()->findChildren($rootCode, $this->getContainer()->getParameter('locale'));

        foreach ($taxons as $taxon) {
            $this->getProductCountByTaxonUpdater()->update($taxon);
            $this->getManager()->flush();
        }

        $this->getManager()->clear();
    }

    /**
     * @return ProductCountByTaxonUpdater|object
     */
    protected function getProductCountByTaxonUpdater()
    {
        return $this->getContainer()->get('app.updater.product_count_by_taxon');
    }

    /**
     * @return TaxonRepository|object
     */
    protected function getTaxonRepository()
    {
        return $this->getContainer()->get('sylius.repository.taxon');
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
