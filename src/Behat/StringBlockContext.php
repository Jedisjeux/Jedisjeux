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

use App\Document\StringBlock;
use Behat\Gherkin\Node\TableNode;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class StringBlockContext extends DefaultContext
{
    /**
     * @Given /^there are string blocks:$/
     * @Given /^there are following string blocks:$/
     * @Given /^the following string blocks exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreStringBlocks(TableNode $table)
    {
        $manager = $this->getDocumentManager();

        foreach ($table->getHash() as $data) {

            /** @var StringBlock $stringBlock */
            $stringBlock = $this->getFactory('string_block', 'app')->createNew();
            $stringBlock->setName(isset($data['name']) ? $data['name'] : $this->faker->slug);
            $stringBlock->setBody(isset($data['body']) ? $data['body'] : $this->faker->realText(400));

            $manager->persist($stringBlock);
        }

        $manager->flush();
    }
}
