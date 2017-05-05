<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\GoogleAnalytics;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SessionService
{
    /**
     * @var \Google_Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $profileId;

    /**
     * SessionCountService constructor.
     *
     * @param \Google_Client $client
     * @param string $profileId
     */
    public function __construct(\Google_Client $client, $profileId)
    {
        $this->client = $client;
        $this->profileId = $profileId;
    }

    /**
     * @param \DateTime $startAt
     * @param \DateTime $endAt
     *
     * @return array
     */
    public function countSessionsPerDay(\DateTime $startAt, \DateTime $endAt)
    {
        $analytics = new \Google_Service_Analytics($this->client);

        $results = $analytics->data_ga->get(
            'ga:' . $this->profileId,
            $startAt->format('Y-m-d'),
            $endAt->format('Y-m-d'),
            'ga:sessions',
            [
                'dimensions' => 'ga:date',
            ]);

        $data = [];

        foreach ($results->getRows() as $row) {
            $data[] = [
                'date' => \DateTime::createFromFormat('Ymd', $row[0]),
                'sessionCount' => (int)$row[1],
            ];
        }

        return $data;
    }

    /**
     * @param \DateTime $startAt
     * @param \DateTime $endAt
     *
     * @return array
     */
    public function countSessionsPerMonth(\DateTime $startAt, \DateTime $endAt)
    {
        $analytics = new \Google_Service_Analytics($this->client);

        $results = $analytics->data_ga->get(
            'ga:' . $this->profileId,
            $startAt->format('Y-m-d'),
            $endAt->format('Y-m-d'),
            'ga:sessions',
            [
                'dimensions' => 'ga:year,ga:month',
            ]);

        $data = [];

        foreach ($results->getRows() as $row) {
            $data[] = [
                'date' => \DateTime::createFromFormat('Ym', $row[0].$row[1]),
                'sessionCount' => (int)$row[2],
            ];
        }

        return $data;
    }
}
