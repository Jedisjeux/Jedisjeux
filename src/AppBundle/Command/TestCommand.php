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

use AppBundle\Entity\Article;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
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
        /** @var Article $article */
        $article = $this->getRepository()->findOneBy(['name' => 'la-liste-essen-2016-est-enfin-la-n-5749']);
        $article->setStatus('pending_review');
        $this->getManager()->flush();

        $output->writeln($article->getStatus());

        $stateMachine = $this->getStateMachine($article);
        $stateMachine->apply('ask_for_publication');

        $this->getManager()->flush();

        $output->writeln($article->getStatus());
    }

    /**
     * @param Article $article
     *
     * @return \SM\StateMachine\StateMachineInterface
     */
    protected function getStateMachine(Article $article)
    {
        /** @var Factory $factory */
        $factory = $this->getContainer()->get('sm.factory');

        return $factory->get($article, 'app_article');
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.article');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
