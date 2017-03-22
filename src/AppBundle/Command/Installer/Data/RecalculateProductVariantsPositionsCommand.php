<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductVariant;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class RecalculateProductVariantsPositionsCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 20;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:product-variants:recalculate-positions')
            ->setDescription('Recalculate positions of all products variants')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command recalculate positions of all product variants.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        $locale = $this->getContainer()->getParameter('locale');

        $i = 0;

        foreach ($this->createProductQueryBuilder($locale)->getQuery()->iterate() as $row) {
            /** @var Product $product */
            $product = $row[0];

            $output->writeln(sprintf("Recalculate positions of <comment>%s</comment> product", $product->getName()));
            $this->recalculatePositions($product);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush();
                $this->getManager()->clear();
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $output->writeln(sprintf("<info>%s</info>", "Positions of all product variants have been successfully recalculated."));
    }

    /**
     * @param Product $product
     */
    protected function recalculatePositions(Product $product)
    {
        /** @var ProductVariant $lastVariant */
        $lastVariant = null;

        /** @var ProductVariant $variant */
        foreach ($product->getVariants() as $variant) {
            if (null === $variant->getReleasedAt()) {
                continue;
            }

            if (null === $lastVariant || $lastVariant->getReleasedAt() < $variant->getReleasedAt()) {
                $lastVariant = $variant;
            }
        }

        if (null === $lastVariant) {
            return;
        }

        $product->setName($lastVariant->getName());
        $lastVariant->setPosition(0);
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('sylius.manager.product');
    }

    /**
     * @param $locale
     *
     * @return QueryBuilder
     */
    protected function createProductQueryBuilder($locale)
    {
        $queryBuilder = $this->getRepository()->createQueryBuilder('o');

        return $queryBuilder;
    }

    /**
     * @return EntityRepository|object
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.product');
    }
}
