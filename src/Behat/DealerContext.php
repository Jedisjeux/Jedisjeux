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
use Behat\Gherkin\Node\TableNode;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerContext extends DefaultContext
{
    /**
     * @Given /^there are dealers:$/
     * @Given /^there are following dealers:$/
     * @Given /^the following dealers exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreDealers(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var Dealer $dealer */
            $dealer = $this->getFactory('dealer', 'app')->createNew();
            $dealer->setCode(isset($data['code']) ? $data['code'] : $this->faker->postcode);
            $dealer->setName(isset($data['name']) ? $data['name'] : $this->faker->name);

            $manager->persist($dealer);
        }

        $manager->flush();
    }
}