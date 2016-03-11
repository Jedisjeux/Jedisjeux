<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 11/03/2016
 * Time: 15:31
 */

namespace AppBundle\Behat;

use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Taxonomy\Model\TaxonomyInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonomyContext extends DefaultContext
{
    /**
     * @Given /^there are taxonomies:$/
     * @Given /^there are following taxonomies:$/
     * @Given /^the following taxonomies exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreTaxonomies(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var TaxonomyInterface $taxonomy */
            $taxonomy = $this->getFactory('taxonomy')->createNew();
            $taxonomy->setCode(isset($data['code']) ? $data['code'] : $this->faker->shuffleString());
            $taxonomy->setName(isset($data['name']) ? $data['name'] : $this->faker->name);

            $manager->persist($taxonomy);
        }

        $manager->flush();
    }
}