<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Product;
use AppBundle\Entity\Redirection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadRedirectionsForProductsCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 20;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:redirections-for-products:load')
            ->setDescription('Load all redirections for products');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $i = 0;

        foreach ($this->createListQueryBuilder()->getQuery()->iterate() as $row) {
            /** @var Product $product */
            $product = $row[0];

            $redirect = $this->createOrReplaceRedirectionForProduct($product);
            $this->getManager()->persist($redirect);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush($redirect); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $output->writeln(sprintf("<info>%s</info>", "Redirects for products have been successfully loaded."));
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createListQueryBuilder()
    {
        $queryBuilder = $this->getProductRepository()->createQueryBuilder('o');
        $queryBuilder
            ->andWhere($queryBuilder->expr()->isNotNull('o.oldHref'));

        return $queryBuilder;
    }

    /**
     * @param Product $product
     *
     * @return Redirection
     */
    protected function createOrReplaceRedirectionForProduct(Product $product)
    {
        $oldHref = '/'.$product->getOldHref();

        /** @var Redirection $redirection */
        $redirection = $this->getRepository()->findOneBy(['source' => $oldHref]);

        if (null === $redirection) {
            $redirection = $this->getFactory()->createNew();
        }

        /** @var Router $router */
        $router = $this->getContainer()->get('router');
        $destination = $router->generate('sylius_product_show', ['slug' => $product->getSlug()]);

        $redirection->setSource($oldHref);
        $redirection->setDestination($destination);
        $redirection->setPermanent(true);

        return $redirection;
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.redirection');
    }

    /**
     * @return FactoryInterface|object
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.redirection');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getProductRepository()
    {
        return $this->getContainer()->get('sylius.repository.product');
    }

    /**
     * @return Router|object
     */
    protected function getRooter()
    {
        return $this->getContainer()->get('router');
    }
}
