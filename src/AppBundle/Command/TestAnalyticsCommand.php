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
use AppBundle\GoogleAnalytics\SessionService;
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
class TestAnalyticsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:analytics:test')
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
        $client = $this->getClient();

        $analytics = new \Google_Service_Analytics($client);

        $results = $analytics->data_ga->get(
            'ga:' . $this->getProfileId(),
            '2017-01-01',
            'today',
            'ga:sessions',
            [
                'dimensions' => 'ga:year,ga:month',
            ]);

        //var_dump($results->getRows());

        $startAt = new \DateTime('first day of January');
        $endAt = new \DateTime('today');

        $results = $this->getSessionService()->countSessionsPerMonth($startAt, $endAt);
        var_dump($results);
    }

    /**
     * @return object|SessionService
     */
    protected function getSessionService()
    {
        return $this->getContainer()->get('app.google_analytics.session');
    }

    /**
     * @return object|\Google_Client
     */
    protected function getClient()
    {
        return $this->getContainer()->get('app.google_analytics.client');
    }

    /**
     * @return string
     */
    protected function getProfileId()
    {
        return $this->getContainer()->getParameter('google.analytics.profile_id');
    }
}
