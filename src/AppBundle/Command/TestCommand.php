<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\Entity\Product;
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use SM\Factory\Factory;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TestCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:test')
            ->setDescription('Tests')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command is a test.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Product $product */
        $product = $this->getRepository()->findOneBySlug('patchwork');
        $product->setStatus('pending_review');
        $this->getManager()->flush();

        $output->writeln($product->getStatus());

        $stateMachine = $this->getStateMachine($product);
        $stateMachine->apply('ask_for_publication');

        $this->getManager()->flush();

        $output->writeln($product->getStatus());
    }

    /**
     * @param ProductInterface $product
     *
     * @return \SM\StateMachine\StateMachineInterface
     */
    protected function getStateMachine(ProductInterface $product)
    {
        /** @var Factory $factory */
        $factory = $this->getContainer()->get('sm.factory');

        return $factory->get($product, 'sylius_product');
    }

    /**
     * @return ProductRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.product');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
