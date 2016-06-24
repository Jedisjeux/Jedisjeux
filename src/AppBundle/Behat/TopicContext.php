<?php

/**
 * Created by PhpStorm.
 * User: loic
 * Date: 23/06/2016
 * Time: 13:48
 */

namespace AppBundle\Behat;

use AppBundle\Entity\Topic;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicContext extends DefaultContext
{
    /**
     * @Given /^there are topics:$/
     * @Given /^there are following topics:$/
     * @Given /^the following topics exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreTopics(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            /** @var TaxonInterface $mainTaxon */
            $mainTaxon = null;
            
            if (isset($data['main-taxon'])) {
                $mainTaxon = $this->getRepository('taxon')->findOneByName($data['main-taxon']);
            }

            /** @var Topic $topic */
            $topic = $this->getFactory('topic', 'app')->createNew();
            $topic
                ->setTitle(isset($data['title']) ? $data['title'] : $this->faker->title)
                ->setMainTaxon($mainTaxon);

            $manager->persist($topic);
            $manager->flush();
        }
    }
}
