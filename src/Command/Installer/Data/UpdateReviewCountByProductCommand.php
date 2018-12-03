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

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Updater\ReviewCountByProductUpdater;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateReviewCountByProductCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:reviews:count-by-product')
            ->setDescription('Update review count by product.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command updates review count by product.
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

        $this->calculateReviewCountByProduct();

        $output->writeln(sprintf('<info>%s</info>', 'Review count by product have been successfully updated.'));
    }

    protected function calculateReviewCountByProduct()
    {
        foreach ($this->createQueryBuilder()->getQuery()->iterate() as $row) {
            /** @var Product $product */
            $product = $row[0];

            $this->getReviewCountByProductUpdater()->update($product);
            $this->getManager()->flush($product);
            $this->getManager()->detach($product);
            $this->getManager()->clear();
        }
    }

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        return $this->getProductRepository()->createQueryBuilder('o');
    }

    /**
     * @return ReviewCountByProductUpdater|object
     */
    protected function getReviewCountByProductUpdater()
    {
        return $this->getContainer()->get('app.updater.review_count_by_product');
    }

    /**
     * @return ProductRepository|object
     */
    protected function getProductRepository()
    {
        return $this->getContainer()->get('sylius.repository.product');
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
