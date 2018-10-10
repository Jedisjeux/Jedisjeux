<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat;

use Behat\Gherkin\Node\TableNode;
use Sylius\Bundle\ContentBundle\Document\StaticContent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class StaticContentContext extends DefaultContext
{
    /**
     * @Given /^there are static contents:$/
     * @Given /^there are following static contents:$/
     * @Given /^the following static contents exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreStaticContents(TableNode $table)
    {
        $manager = $this->getDocumentManager();

        foreach ($table->getHash() as $data) {

            /** @var StaticContent $staticContent */
            $staticContent = $this->getFactory('static_content', 'sylius')->createNew();
            $staticContent->setName(isset($data['name']) ? $data['name'] : $this->faker->slug);
            $staticContent->setTitle(isset($data['title']) ? $data['title'] : $this->faker->title);
            $staticContent->setBody(isset($data['body']) ? $data['body'] : $this->faker->realText(400));

            $manager->persist($staticContent);
        }

        $manager->flush();
    }
}
