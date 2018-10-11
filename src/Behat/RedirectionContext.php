<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat;

use App\Entity\Dealer;
use App\Entity\Redirection;
use Behat\Gherkin\Node\TableNode;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class RedirectionContext extends DefaultContext
{
    /**
     * @Given /^there are redirections:$/
     * @Given /^there are following redirections:$/
     * @Given /^the following redirections exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreRedirections(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var Redirection $redirection */
            $redirection = $this->getFactory('redirection', 'app')->createNew();
            $redirection->setSource(isset($data['source']) ? $data['source'] : $this->faker->url);
            $redirection->setDestination(isset($data['destination']) ? $data['destination'] : $this->faker->url);
            $redirection->setPermanent(isset($data['permanent']) ? (bool) $data['permanent'] : $this->faker->boolean(80));

            $manager->persist($redirection);
        }

        $manager->flush();
    }
}