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
use AppBundle\Entity\ProductVariant;
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
class LoadRedirectionsForProductsCommand extends AbstractLoadRedirectionsCommand
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
            /** @var ProductVariant $productVariant */
            $productVariant = $row[0];

            $output->writeln(sprintf("Loading redirection for <comment>%s</comment> url", '/'.$productVariant->getOldHref()));

            $redirect = $this->createOrReplaceRedirectionForProductVariant($productVariant);
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
        $queryBuilder = $this->getProductVariantRepository()->createQueryBuilder('o');
        $queryBuilder
            ->andWhere($queryBuilder->expr()->isNotNull('o.oldHref'));

        return $queryBuilder;
    }

    /**
     * @param ProductVariant $productVariant
     *
     * @return Redirection
     */
    protected function createOrReplaceRedirectionForProductVariant(ProductVariant $productVariant)
    {
        $oldHref = '/'.$productVariant->getOldHref();

        /** @var Redirection $redirection */
        $redirection = $this->getRepository()->findOneBy(['source' => $oldHref]);

        if (null === $redirection) {
            $redirection = $this->getFactory()->createNew();
        }

        $destination = $this->getRooter()->generate('sylius_frontend_product_show', ['slug' => $productVariant->getProduct()->getSlug()]);

        $redirection->setSource($oldHref);
        $redirection->setDestination($destination);
        $redirection->setPermanent(true);

        return $redirection;
    }

    /**
     * @return EntityRepository|object
     */
    protected function getProductVariantRepository()
    {
        return $this->getContainer()->get('sylius.repository.product_variant');
    }
}
