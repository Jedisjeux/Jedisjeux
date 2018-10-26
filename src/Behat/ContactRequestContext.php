<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat;

use App\Entity\ContactRequest;
use Behat\Gherkin\Node\TableNode;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ContactRequestContext extends DefaultContext
{
    /**
     * @Given /^there are contact requests:$/
     * @Given /^there are following contact requests:$/
     * @Given /^the following contact requests exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreContactRequests(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var ContactRequest $contactRequest */
            $contactRequest = $this->getFactory('contact_request', 'app')->createNew();
            $contactRequest->setFirstName(isset($data['first_name']) ? $data['first_name'] : $this->faker->firstName);
            $contactRequest->setLastName(isset($data['last_name']) ? $data['last_name'] : $this->faker->lastName);
            $contactRequest->setEmail(isset($data['email']) ? $data['email'] : $this->faker->email);
            $contactRequest->setBody(isset($data['body']) ? $data['body'] : $this->faker->realText());

            $manager->persist($contactRequest);
        }

        $manager->flush();
    }
}
