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

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Widop\GoogleAnalytics\Client;
use Widop\GoogleAnalytics\Query;
use Widop\GoogleAnalytics\Service;

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
        $service = $this->getService();

        $service->getClient()->getAccessToken();

        $query = $this->getQuery();
        $query
            ->setStartDate(new \DateTime('2 days ago'))
            ->setEndDate(new \DateTime())
            ->setMetrics(array('ga:visits' ,'ga:bounces'));

        $response = $service->query($query);
    }

    /**
     * @return Service
     */
    protected function getService()
    {
        return $this->getContainer()->get('widop_google_analytics');
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        return $this->getContainer()->get('widop_google_analytics.client');
    }

    /**
     * @return Query
     */
    protected function getQuery()
    {
        return $this->getContainer()->get('widop_google_analytics.query');
    }
}
