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

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Updater\CommentedReviewCountByProductUpdater;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommentedReviewCountByProductCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:commented-reviews:count-by-product')
            ->setDescription('Update commented review count by product.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command updates commented review count by product.
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

        $this->calculateCommentedReviewCountByProduct();

        $output->writeln(sprintf('<info>%s</info>', 'Commented review count by product have been successfully updated.'));
    }

    protected function calculateCommentedReviewCountByProduct()
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
     * @return CommentedReviewCountByProductUpdater|object
     */
    protected function getReviewCountByProductUpdater()
    {
        return $this->getContainer()->get('App\Updater\CommentedReviewCountByProductUpdater');
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
